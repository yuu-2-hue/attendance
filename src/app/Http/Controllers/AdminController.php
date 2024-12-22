<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function attendanceList()
    {
        return view('admin.attendance-list');
    }

    public function detail()
    {
        return view('admin.attendance-detail');
    }

    public function staffList()
    {
        return view('admin.staff-list');
    }

    public function staffAttendanceList()
    {
        return view('admin.staff-attendance-list');
    }

    public function request(Request $request)
    {
        $tab = $request->query('tab');
        if($tab == null) $tab = 'unfinished';

        return view('admin.attendance-request', compact('tab'));
    }

    public function approve()
    {
        
    }
}
