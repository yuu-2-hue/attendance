<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;
use App\Models\Attendance;

class AttendanceController extends Controller
{
    public function attendance()
    {
        $todayAttendanceData = Attendance::GetTodayAttendance();

        $status = '勤務外';
        if(isset($todayAttendanceData->user_id) == true)
        {
            $status = $todayAttendanceData->status;
        }

        return view('user.attendance', compact('status'));
    }

    public function list()
    {
        return view('user.attendance-list');
    }

    public function request(Request $request)
    {
        $tab = $request->query('tab');
        if($tab == null) $tab = 'unfinished';

        return view('user.attendance-request', compact('tab'));
    }

    public function detail()
    {
        return view('user.attendance-detail');
    }

    public function store(Request $request)
    {
        // 現在の時間を文字列に変換
        $date = $request->year.'-'.$request->month.'-'.$request->day;
        $time = $request->hour.':'.$request->min;

        if($request->has('at_work'))
        {
            Attendance::create([
                'user_id' => Auth::id(),
                'status' => '勤務中',
                'date' => $date,
                'at_work_time' => $time,
                'finish_work_time' => $time,
                'at_rest_time' => $time,
                'finish_rest_time' => $time,
                'total_time' => '08:00',
            ]);
        }
        else if($request->has('finish_work'))
        {
            $todayAttendanceData = Attendance::GetTodayAttendance();

            // 働いている時間
            $startDate = new Carbon($todayAttendanceData->date.' '.$todayAttendanceData->at_work_time);
            $endDate = new Carbon($request->year.'-'.$request->month.'-'.$request->day.' '.$request->hour.':'.$request->min);
            // 休憩時間
            $startRestTime = new Carbon($todayAttendanceData->date.' '.$todayAttendanceData->at_rest_time);
            $endRestTime = new Carbon($todayAttendanceData->date.' '.$todayAttendanceData->finish_rest_time);

            // 働いている合計時間を計算
            $workSecond = $startDate->diffInSeconds($endDate);
            $restSecond = $startRestTime->diffInSeconds($endRestTime);
            $totalSecond = $workSecond - $restSecond;

            // 変換
            $workHour = floor($totalSecond / 3600);
            $workMinute = floor(($totalSecond % 3600) / 60);
            $totalTime = $workHour.':'.$workMinute;

            Attendance::GetTodayAttendance()->update([
                'status' => '退勤済',
                'finish_work_time' => $time,
                'total_time' => $totalTime,
            ]);
        }
        else if($request->has('at_rest'))
        {
            Attendance::GetTodayAttendance()->update([
                'status' => '休憩中',
                'at_rest_time' => $time,
            ]);
        }
        else if($request->has('finish_rest'))
        {
            Attendance::GetTodayAttendance()->update([
                'status' => '勤務中',
                'finish_rest_time' => $time,
            ]);
        }

        return redirect()->route('user.attendance');
    }
}
