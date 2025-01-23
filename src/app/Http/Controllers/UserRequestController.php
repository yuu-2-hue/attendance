<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Apply;

class UserRequestController extends Controller
{
    public function request(Request $request)
    {
        $applies = Apply::where('user_id', Auth::id())->get();

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

        return view('user.attendance-request', compact('tab', 'lists'));
    }
}
