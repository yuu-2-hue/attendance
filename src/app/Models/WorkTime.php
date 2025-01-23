<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

class WorkTime extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'at_work_date',
        'finish_work_date',
        'at_work_time',
        'finish_work_time',
        'total_work_time',
    ];

    // 今日の勤怠を取得
    public function scopeGetTodayAttendance($query, $yesterday, $today)
    {
        foreach($this->get() as $data)
        {
            if($data->user_id == Auth::id())
            {
                if($data->at_work_date == $yesterday && $data->status != '退勤済')
                {
                    return $data;
                }
                else if($data->at_work_date == $today && $data->status != '退勤済')
                {
                    return $data;
                }
                else if($data->at_work_date == $today && $data->status == '退勤済')
                {
                    return $data;
                }
            }
        }

        return null;
    }

    // 指定した月の勤怠データを取得
    public function scopeGetThisMonthAttendance($query, $value)
    {
        // 指定した月の最初の日付を取得
        $targetStartMonth = new Carbon($value.' months');
        $targetMonthStartDate = $targetStartMonth->startOfMonth();
        // 指定した月の最後の日付を取得
        $targetFinishMonth = new Carbon($value.' months');
        $targetMonthFinishDate = $targetFinishMonth->startOfMonth()->addMonthNoOverflow()->subSecond(1);

        // 取得
        $data = $this->whereBetween('at_work_date', array($targetMonthStartDate, $targetMonthFinishDate))->get();

        return $data;
    }

    // 指定した日の勤怠データを取得
    public function scopeGetThisDayAttendance($query, $value)
    {
        // 指定した月の最初の日付を取得
        $targetStartDay = new Carbon($value.' day');
        $targetMonthStartDate = $targetStartDay->startOfDay();
        // 指定した月の最後の日付を取得
        $targetFinishDay = new Carbon($value.' day');
        $targetMonthFinishDate = $targetFinishDay->startOfDay()->addDay()->subSecond(1);

        // 取得
        $data = $this->whereBetween('at_work_date', array($targetMonthStartDate, $targetMonthFinishDate))
                    ->orWhereBetween('finish_work_date', array($targetMonthStartDate, $targetMonthFinishDate))->get();

        return $data;
    }

    // 働いている時間を秒数に直す
    public function scopeGetWorkSecond($query, $startDate, $finishDate)
    {
        return $startDate->diffInSeconds($finishDate);
    }

    // 働いている時間を取得
    public function scopeGetWorkTime($query, $startDate, $finishDate)
    {
        $workSecond = $startDate->diffInSeconds($finishDate);

        $workHour = floor($workSecond / 3600);
        $workMinute = floor(($workSecond % 3600) / 60);

        return $workHour.':'.$workMinute;
    }

    // 働いている時間だけを計算
    public function scopeGetTotalWorkTime($query, $workTime, $restTime)
    {
        list($hours, $minutes, $seconds) = explode(':', $workTime);
        $workSeconds = $hours * 3600 + $minutes * 60 + $seconds;

        list($hours, $minutes) = explode(':', $restTime);
        $restSeconds = $hours * 3600 + $minutes * 60;

        $totalWorkSeconds = $workSeconds - $restSeconds;

        $hour = str_pad(floor($totalWorkSeconds / 3600), 2, '0', STR_PAD_LEFT);
        $minute = str_pad(floor(($totalWorkSeconds % 3600) / 60), 2, '0', STR_PAD_LEFT);

        return $hour.':'.$minute;
    }
}
