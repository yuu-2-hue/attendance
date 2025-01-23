<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use DateTime;
use App\Models\RestTime;

class RestTimesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $atWorkDate = new DateTime('2025-01-01');
        $finishWorkDate = new DateTime('2025-01-01');
        for ($i = 0; $i < 100; $i++) {
            RestTime::factory(1)
                ->create([
                    'work_time_id' => $i+1,
                    'at_rest_date' => $atWorkDate->modify('+1day')->format('Y-m-d'),
                    'finish_rest_date' => $finishWorkDate->modify('+1day')->format('Y-m-d'),
                ]);
        }
    }
}
