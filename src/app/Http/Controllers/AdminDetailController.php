<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use App\Http\Requests\DetailRequest;
use App\Models\WorkTime;
use App\Models\RestTime;
use App\Models\Apply;
use App\Models\User;

class AdminDetailController extends Controller
{
    public function detail($workId)
    {
        $workTime = WorkTime::Find($workId);
        $restData = RestTime::where('work_time_id', $workId)->get();
        $applies = Apply::where('work_time_id', $workId)->get();

        $status = '';
        foreach($applies as $apply)
        {
            if($apply->status != null) $status = $apply->status;
        }

        $user = User::Find($workTime->user_id);

        $workData = array(
            'id' => $workId,
            'name' => $user->name,
            'year' => date(('Y年'), strtotime($workTime->at_work_date)),
            'date' => date(('n月j日'), strtotime($workTime->at_work_date)),
            'at_work_time' => substr($workTime->at_work_time, 0, 5),
            'finish_work_time' => substr($workTime->finish_work_time, 0, 5),
        );

        return view('admin.attendance-detail', compact('workData', 'restData', 'status'));
    }

    public function retouch(DetailRequest $request, $workId)
    {
        $workTime = WorkTime::Find($workId);
        $user = User::Find($workTime->user_id);
        $applies = Apply::where('work_time_id', $workId)->get();
        $restTimes = RestTime::where('work_time_id', $workId)->get();

        $today = Carbon::now()->format('Y/m/d');
        $targetDay = Carbon::createFromFormat('Y年m月d日', $request->year.$request->date)->format('Y-m-d');

        // 申請データベースに登録
        if(count($applies) != 0)
        {
            Apply::where('work_time_id', $workId)->update([
                'work_time_id' => $workId,
                'user_id' => $user->id,
                'status' => '承認待ち',
                'name' => $user->name,
                'target_date' => $targetDay,
                'request_date' => $today,
                'reason' => $request->reason,
            ]);
        }
        else
        {
            Apply::create([
                'work_time_id' => $workId,
                'user_id' => $user->id,
                'status' => '承認待ち',
                'name' => $user->name,
                'target_date' => $targetDay,
                'request_date' => $today,
                'reason' => $request->reason,
            ]);
        }

        // 休憩時間のデータベースを更新
        for($i = 0; $i < count($restTimes); $i++)
        {
            $restTimes[$i]->update([
                'at_rest_time' => $request->at_rest_time[$i],
                'finish_rest_time' => $request->finish_rest_time[$i],
                'total_rest_time' => RestTime::CalculationTotalRestTime($request->at_rest_time[$i], $request->finish_rest_time[$i]),
            ]);
        }

        // 労働時間のデータベースを更新
        $atWorkDate = new Carbon($workTime->at_work_date.' '.$request->at_work_time);
        $finishWorkDate = new Carbon($workTime->finish_work_date.' '.$request->finish_work_time);
        $workTime->update([
            'date' => Carbon::createFromFormat('Y年m月d日', $request->year.$request->date)->format('Y-m-d'),
            'at_work_time' => $request->at_work_time,
            'finish_work_time' => $request->finish_work_time,
            'total_work_time' => WorkTime::GetWorkTime($atWorkDate, $finishWorkDate),
        ]);

        return redirect()->route('admin.detail', $request->id);
    }
}
