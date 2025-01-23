<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;
use App\Models\WorkTime;
use App\Models\RestTime;

class UserListController extends Controller
{
    public function list(Request $request)
    {
        $value = $request->session()->get('userListMonthValue');
        if(!isset($value)) $value = 0;

        $workTimes = WorkTime::GetThisMonthAttendance($value);
        $targetMonth = new Carbon($value.' months');
        $month = $targetMonth->format('Y年m月');

        Carbon::setLocale('ja');

        $lists = [];
        foreach($workTimes as $workTime)
        {
            if($workTime->user_id == Auth::id())
            {
                $atWorkTime = substr($workTime->at_work_time, 0, 5);
                $finishWorkTime = substr($workTime->finish_work_time, 0, 5);

                $carbon = new Carbon($workTime->at_work_date);
                $date = $carbon->isoFormat('MM/DD (ddd)');

                $restTime = RestTime::GetTotalRestTime($workTime->id);

                if($workTime->total_work_time != null) $totalWorkTime = WorkTime::GetTotalWorkTime($workTime->total_work_time, $restTime);
                else $totalWorkTime = '';

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

        return view('user.attendance-list', compact('lists', 'month'));
    }

    public function changeMonth(Request $request)
    {
        $value = $request->session()->get('userListMonthValue');
        if(!isset($value)) $value = 0;

        if($request->has('previous-month')) $value -= 1;
        else if($request->has('next-month')) $value += 1;

        $request->session()->put('userListMonthValue', $value);

        return redirect()->route('user.list');
    }
}
