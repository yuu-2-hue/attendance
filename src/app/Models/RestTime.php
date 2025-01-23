<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class RestTime extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_time_id',
        'status',
        'at_rest_date',
        'finish_rest_date',
        'at_rest_time',
        'finish_rest_time',
        'total_rest_time',
    ];

    public function scopeGetRestTime($query, $workTimeId)
    {
        foreach($this->get() as $data)
        {
            if($data->work_time_id == $workTimeId && $data->status == '休憩中')
            {
                return $data;
            }
        }

        return null;
    }

    public function scopeCalculationTotalRestTime($query, $atRestDate, $finishRestDate)
    {
        $startTime = new Carbon($atRestDate);
        $endTime = new Carbon($finishRestDate);

        $diffInSeconds = $startTime->diffInSeconds($endTime);

        $hour = floor($diffInSeconds / 3600);
        $minute = floor(($diffInSeconds % 3600) / 60);

        return $hour.':'.$minute;
    }

    public function scopeGetTotalRestSecond($query, $id)
    {
        $restTimes = $this->where('work_time_id', $id)->get();
        $totalRestSecond = 0;
        foreach($restTimes as $rest)
        {
            $startSecond = new Carbon($rest->at_rest_date.' '.$rest->at_rest_time);
            $endSecond = new Carbon($rest->finish_rest_date.' '.$rest->finish_rest_time);
            $totalSecond = $startSecond->diffInSeconds($endSecond);
            $totalRestSecond += $totalSecond;
        }

        return $totalRestSecond;
    }

    public function scopeGetTotalRestTime($query, $id)
    {
        $totalRestSecond = $this::GetTotalRestSecond($id);

        // 変換
        $restHour = floor($totalRestSecond / 3600);
        $restMinute = floor(($totalRestSecond % 3600) / 60);

        $carbon = new Carbon($restHour.':'.$restMinute);
        $time = $carbon->format('H:i');

        return $time;
    }
}
