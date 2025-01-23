<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

use App\Models\WorkTime;
use App\Models\RestTime;
use App\Models\Apply;
use App\Models\User;

class AdminRequestController extends Controller
{
    public function request(Request $request)
    {
        $applies = Apply::All();

        $tab = $request->query('tab');
        if($tab == null) $tab = 'unfinished';

        $lists = [];
        foreach($applies as $apply)
        {
            if($apply->status == '承認済み' && $tab == 'finished')
            {
                array_push($lists, array(
                    'user_id' => $apply->user_id,
                    'work_time_id' => $apply->work_time_id,
                    'status' => $apply->status,
                    'name' => $apply->name,
                    'target_date' => date(('Y/m/d'), strtotime($apply->target_date)),
                    'request_date' => date(('Y/m/d'), strtotime($apply->request_date)),
                    'reason' => $apply->reason,
                ));
            }
            else if($apply->status == '承認待ち' && $tab == 'unfinished')
            {
                array_push($lists, array(
                    'user_id' => $apply->user_id,
                    'work_time_id' => $apply->work_time_id,
                    'status' => $apply->status,
                    'name' => $apply->name,
                    'target_date' => date(('Y/m/d'), strtotime($apply->target_date)),
                    'request_date' => date(('Y/m/d'), strtotime($apply->request_date)),
                    'reason' => $apply->reason,
                ));
            }
        }

        return view('admin.attendance-request', compact('tab', 'lists'));
    }

    public function approve($workId)
    {
        $workTime = WorkTime::Find($workId);
        $user = User::Find($workTime->user_id);
        $applies = Apply::where('work_time_id', $workId)->get();

        $status = '';
        foreach($applies as $apply)
        {
            if($apply->status != null) $status = $apply->status;
        }

        $workData = array(
            'id' => $workId,
            'name' => $user->name,
            'year' => date(('Y年'), strtotime($workTime->at_work_date)),
            'date' => date(('n月j日'), strtotime($workTime->at_work_date)),
            'at_work_time' => substr($workTime->at_work_time, 0, 5),
            'finish_work_time' => substr($workTime->finish_work_time, 0, 5),
        );

        $restData = RestTime::where('work_time_id', $workId)->get();

        return view('admin.attendance-approve', compact('workData', 'restData', 'status', 'workId'));
    }

    public function update(Request $request, $workId)
    {
        $user = User::Find(Auth::id());
        $today = Carbon::now()->format('Y/m/d');
        $targetDay = Carbon::createFromFormat('Y年m月d日', $request->year.$request->date)->format('Y-m-d');

        Apply::where('work_time_id', $workId)->update([
            'status' => '承認済み',
        ]);

        return redirect()->route('admin.approve', $workId);
    }
}
