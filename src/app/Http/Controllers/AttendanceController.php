<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function attendance()
    {
        return view('user.attendance');
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
}
