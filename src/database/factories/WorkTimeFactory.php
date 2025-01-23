<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class WorkTimeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => '',
            'status' => '退勤済み',
            'at_work_date' => '',
            'finish_work_date' => '',
            'at_work_time' => '08:00',
            'finish_work_time'=> '17:00',
            'total_work_time' => '09:00',
        ];
    }
}
