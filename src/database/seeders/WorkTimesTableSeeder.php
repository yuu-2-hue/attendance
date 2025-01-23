<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use DateTime;
use App\Models\WorkTime;

class WorkTimesTableSeeder extends Seeder
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
        for ($i = 0; $i < 100; $i++)
        {
            WorkTime::factory(1)
                ->create([
                    'user_id' => ($i%5)+1,
                    'at_work_date' => $atWorkDate->modify('+1day')->format('Y-m-d'),
                    'finish_work_date' => $finishWorkDate->modify('+1day')->format('Y-m-d'),
                ]);
        }
    }
}
