<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function attendance()
    {
        return view('attendance');
    }

    public function list()
    {
        return view('attendance-list');
    }

    public function request(Request $request)
    {
        $tab = $request->query('tab');
        if($tab == null) $tab = 'unfinished';

        return view('attendance-request', compact('tab'));
    }

    public function detail()
    {
        return view('attendance-detail');
    }
}
