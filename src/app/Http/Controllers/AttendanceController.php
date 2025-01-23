<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;
use App\Models\WorkTime;
use App\Models\RestTime;

class AttendanceController extends Controller
{
    public function attendance()
    {
        $today = Carbon::today()->format('Y-m-d');
        $yesterday = Carbon::yesterday()->format('Y-m-d');
        $todayAttendanceData = WorkTime::GetTodayAttendance($yesterday, $today);

        $status = '勤務外';
        if(isset($todayAttendanceData->user_id) == true)
        {
            $status = $todayAttendanceData->status;
        }

        return view('user.attendance', compact('status'));
    }

    public function store(Request $request)
    {
        $today = Carbon::today()->format('Y-m-d');
        $yesterday = Carbon::yesterday()->format('Y-m-d');
        $todayAttendanceData = WorkTime::GetTodayAttendance($yesterday, $today);

        // 現在の時間を文字列に変換
        $date = $request->year.'-'.$request->month.'-'.$request->day;
        $time = $request->hour.':'.$request->min.':'.$request->sec;

        if($request->has('at_work'))
        {
            WorkTime::create([
                'user_id' => Auth::id(),
                'status' => '勤務中',
                'at_work_date' => $date,
                'at_work_time' => $time,
            ]);
        }
        else if($request->has('finish_work'))
        {
            // 働いている時間
            $startDate = new Carbon($todayAttendanceData->at_work_date.' '.substr($todayAttendanceData->at_work_time, 0, 5));
            $finishDate = new Carbon($request->year.'-'.$request->month.'-'.$request->day.' '.$request->hour.':'.$request->min);

            $totalTime = WorkTime::GetWorkTime($startDate, $finishDate);

            $todayAttendanceData->update([
                'status' => '退勤済',
                'finish_work_date' => $date,
                'finish_work_time' => $time,
                'total_work_time' => $totalTime,
            ]);
        }
        else if($request->has('at_rest'))
        {
            $todayAttendanceData->update([
                'status' => '休憩中',
            ]);

            RestTime::create([
                'status' => '休憩中',
                'work_time_id' => $todayAttendanceData->id,
                'at_rest_date' => $date,
                'at_rest_time' => $time,
            ]);
        }
        else if($request->has('finish_rest'))
        {
            $restTime = RestTime::GetRestTime($todayAttendanceData->id);

            $todayAttendanceData->update([
                'status' => '勤務中',
            ]);

            $startRestDate = $restTime->at_rest_date.' '.substr($restTime->at_rest_time, 0, 5);
            $finishRestDate = $date.' '.substr($time, 0, 5);
            $totalRestTime = RestTime::CalculationTotalRestTime($startRestDate, $finishRestDate);

            $restTime->update([
                'status' => '休憩終了',
                'finish_rest_date' => $date,
                'finish_rest_time' => $time,
                'total_rest_time' => $totalRestTime,
            ]);
        }

        return redirect()->route('user.attendance');
    }
}
