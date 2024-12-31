<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'date',
        'at_work_time',
        'finish_work_time',
        'at_rest_time',
        'finish_rest_time',
        'total_time',
    ];

    // public function scopeGetAttendance($query, $date)
    // {
    //     if($this->where('user_id', Auth::id())->exists())
    //     {
    //         if($this->where('date', $date)->exists())
    //         {
    //             return $this->where('date', $date);
    //         }
    //     }

    //     return null;
    // }

    public function scopeGetTodayAttendance()
    {
        foreach($this->get() as $data)
        {
            if($data->user_id == Auth::id())
            {
                if($data->status == '退勤済')
                {
                    return $data;
                }
                return $data;
            }
        }

        return null;
    }
}
