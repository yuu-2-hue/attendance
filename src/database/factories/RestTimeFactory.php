<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RestTimeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'work_time_id' => '',
            'status' => '休憩終了',
            'at_rest_date' => '',
            'finish_rest_date' => '',
            'at_rest_time' => '12:00',
            'finish_rest_time' => '13:00',
            'total_rest_time' => '01:00',
        ];
    }
}
