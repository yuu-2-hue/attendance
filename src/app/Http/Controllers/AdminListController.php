<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;

use App\Models\User;
use App\Models\WorkTime;
use App\Models\RestTime;

class AdminListController extends Controller
{
    public function attendanceList(Request $request)
    {
        $value = $request->session()->get('adminListDayValue');
        if(!isset($value)) $value = 0;

        $workTimes = WorkTime::GetThisDayAttendance($value);
        $targetDay = new Carbon($value.' day');
        $day = $targetDay->format('Y/m/d');

        Carbon::setLocale('ja');

        $lists = [];
        foreach($workTimes as $workTime)
        {
            $atWorkDate = $workTime->at_work_date;
            $atWorkTime = substr($workTime->at_work_time, 0, 5);
            $finishWorkTime = substr($workTime->finish_work_time, 0, 5);

            $carbon = new Carbon($atWorkDate);
            $date = $carbon->isoFormat('MM/DD (ddd)');

            $restTime = RestTime::GetTotalRestTime($workTime->id);

            if($workTime->total_work_time != null) $totalWorkTime = WorkTime::GetTotalWorkTime($workTime->total_work_time, $restTime);
            else $totalWorkTime = '';

            array_push($lists, array(
                'id' => $workTime->id,
                'name' => User::Find($workTime->user_id)->name,
                'date' => $date,
                'at_work_time' => $atWorkTime,
                'finish_work_time' => $finishWorkTime,
                'rest_time' => $restTime,
                'total_work_time' => $totalWorkTime,
            ));
        }

        return view('admin.attendance-list', compact('lists', 'day'));
    }

    public function changeDay(Request $request)
    {
        $value = $request->session()->get('adminListDayValue');
        if(!isset($value)) $value = 0;

        if($request->has('previous-day')) $value -= 1;
        else if($request->has('next-day')) $value += 1;

        $request->session()->put('adminListDayValue', $value);

        return redirect()->route('admin.list');
    }
}
