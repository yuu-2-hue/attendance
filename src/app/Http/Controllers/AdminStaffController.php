<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;

use App\Models\User;
use App\Models\WorkTime;
use App\Models\RestTime;

use Illuminate\Support\Facades\Date;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminStaffController extends Controller
{
    public function staffList()
    {
        $users = User::All();

        return view('admin.staff-list', compact('users'));
    }

    public function staffAttendanceList(Request $request, $userId)
    {
        $name = User::Find($userId)->name;

        $value = $request->session()->get('adminListMonthValue');
        if(!isset($value)) $value = 0;

        $workTimes = WorkTime::GetThisMonthAttendance($value);
        $targetDate = new Carbon($value.' months');
        $month = $targetDate->format('Y/m');

        Carbon::setLocale('ja');

        $lists = [];
        foreach($workTimes as $workTime)
        {
            if($workTime->user_id == $userId)
            {
                $carbon = new Carbon($workTime->at_work_date);
                $date = $carbon->isoFormat('MM/DD (ddd)');

                $atWorkTime = substr($workTime->at_work_time, 0, 5);
                $finishWorkTime = substr($workTime->finish_work_time, 0, 5);

                $restTime = RestTime::GetTotalRestTime($workTime->id);

                $totalWorkTime = WorkTime::GetTotalWorkTime($workTime->total_work_time, $restTime);

                array_push($lists, array(
                    'id' => $workTime->id,
                    'date' => $date,
                    'at_work_time' => $atWorkTime,
                    'finish_work_time' => $finishWorkTime,
                    'rest_time' => $restTime,
                    'total_work_time' => $totalWorkTime,
                ));
            }
        }

        return view('admin.staff-attendance-list', compact('lists', 'month', 'name', 'userId'));
    }

    public function changeMonth(Request $request, $userId)
    {
        $value = $request->session()->get('adminListMonthValue');
        if(!isset($value)) $value = 0;

        if($request->has('previous-month')) $value -= 1;
        else if($request->has('next-month')) $value += 1;

        $request->session()->put('adminListMonthValue', $value);

        return redirect()->route('admin.staff-attendance-list', $userId);
    }

    public function export(Request $request)
    {
        $value = $request->session()->get('adminListMonthValue');
        if(!isset($value)) $value = 0;
        $workTimes = WorkTime::GetThisMonthAttendance($value);

        $csvData = [];
        foreach($workTimes as $workTime)
        {
            if($workTime->user_id == $request->userId)
            {
                $restTime = RestTime::GetTotalRestTime($workTime->id);
                $totalWorkTime = WorkTime::GetTotalWorkTime($workTime->total_work_time, $restTime);
                array_push($csvData, array(
                    'id' => $workTime->id,
                    'at_work_time' => substr($workTime->at_work_time, 0, 5),
                    'finish_work_time' => substr($workTime->finish_work_time, 0, 5),
                    'total_rest_time' => $restTime,
                    'total_work_time' => $totalWorkTime,
                    'created_at' => $workTime->created_at,
                    'updated_at' => $workTime->updated_at,
                ));
            }
        }

        $csvHeader = [
            'id', 'at_work_time', 'finish_work_time', 'total_rest_time', 'total_work_time', 'created_at', 'updated_at'
        ];

        $response = new StreamedResponse(function () use ($csvHeader, $csvData) {
            $createCsvFile = fopen('php://output', 'w');

            mb_convert_variables('SJIS-win', 'UTF-8', $csvHeader);

            fputcsv($createCsvFile, $csvHeader);

            foreach ($csvData as $csv) {
                $csv['created_at'] = Date::make($csv['created_at'])->setTimezone('Asia/Tokyo')->format('Y/m/d H:i:s');
                $csv['updated_at'] = Date::make($csv['updated_at'])->setTimezone('Asia/Tokyo')->format('Y/m/d H:i:s');
                fputcsv($createCsvFile, $csv);
            }

            fclose($createCsvFile);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="contacts.csv"',
        ]);

        return $response;
    }
}
