<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

use Carbon\Carbon;
use App\Models\User;
use App\Models\WorkTime;
use App\Models\RestTime;

class AttendanceControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh');
        $this->seed();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /** @test */
    public function AccessAttendanceView()
    {
        $user = User::Find(1);
        $this->actingAs($user);

        $today = new Carbon('2025-01-10');
        $yesterday = new Carbon('2025-01-09');
        $todayAttendanceData = WorkTime::GetTodayAttendance($yesterday, $today)->get();

        $status = '勤務外';
        foreach($todayAttendanceData as $data)
        {
            if(isset($data->user_id) == true) $status = $data->status;
        }
        $this->assertEquals($status, '退勤済み');

        $response = $this->get(route('user.attendance'));
        $this->assertTrue(Auth::check());
        $response->assertStatus(200);
        $response->assertViewIs('user.attendance');
        // $response->assertSee($status);
    }

    /** @test */
    public function PushAtWorkButton()
    {
        $date = new Carbon('2025-01-19');
        $time = new Carbon('18:00:00');

        WorkTime::create([
            'user_id' => 1,
            'status' => '勤務中',
            'at_work_date' => $date,
            'at_work_time' => $time,
        ]);

        $this->assertDatabaseHas('work_times',[
            'user_id' => 1,
            'status' => '勤務中',
            'at_work_date' => '2025-01-19',
            'at_work_time' => '18:00:00',
        ]);
    }

    /** @test */
    public function PushFinishWorkButton()
    {
        $date = new Carbon('2025-01-19');
        $time = new Carbon('18:00:00');
        WorkTime::create([
            'user_id' => 1,
            'status' => '勤務中',
            'at_work_date' => $date,
            'at_work_time' => $time,
        ]);

        // 働いている時間
        $startDate = new Carbon('2025-01-19 18:00');
        $finishDate = new Carbon('2025-01-19 19:00:00');
        $totalTime = WorkTime::GetWorkTime($startDate, $finishDate);
        WorkTime::Find(6)->update([
            'status' => '退勤済',
            'finish_work_date' => $startDate,
            'finish_work_time' => $finishDate,
            'total_work_time' => $totalTime,
        ]);

        $this->assertDatabaseHas('work_times',[
            'id' => 6,
            'user_id' => 1,
            'status' => '退勤済',
            'at_work_date' => '2025-01-19',
            'finish_work_date' => '2025-01-19',
            'at_work_time' => '18:00:00',
            'finish_work_time' => '19:00:00',
            'total_work_time' => '01:00:00',
        ]);
    }

    /** @test */
    public function PushAtRestButton()
    {
        $workTime = WorkTime::Find(1);
        $workTime->update([
            'status' => '休憩中',
        ]);
        $this->assertEquals($workTime->status, '休憩中');

        // 働いている時間
        $date = new Carbon('2025-01-19');
        $time = new Carbon('18:30:00');
        RestTime::create([
            'status' => '休憩中',
            'work_time_id' => $workTime->id,
            'at_rest_date' => $date,
            'at_rest_time' => $time,
        ]);

        $this->assertDatabaseHas('rest_times',[
            'status' => '休憩中',
            'work_time_id' => 1,
            'at_rest_date' => '2025-01-19',
            'at_rest_time' => '18:30:00',
        ]);
    }

    /** @test */
    public function PushFinishRestButton()
    {
        $workTime = WorkTime::Find(1);
        $workTime->update([
            'status' => '休憩中',
        ]);

        $date = new Carbon('2025-01-19');
        $time = new Carbon('18:30:00');
        RestTime::create([
            'status' => '休憩中',
            'work_time_id' => $workTime->id,
            'at_rest_date' => $date,
            'at_rest_time' => $time,
        ]);

        $workTime->update([
            'status' => '勤務中',
        ]);
        $startRestDate = new Carbon('2025-01-19 18:30');
        $finishRestDate = new Carbon('2025-01-19 18:40');
        $totalRestTime = RestTime::CalculationTotalRestTime($startRestDate, $finishRestDate);

        RestTime::Find(6)->update([
            'status' => '休憩終了',
            'finish_rest_date' => '2025-01-19',
            'finish_rest_time' => '18:40',
            'total_rest_time' => $totalRestTime,
        ]);

        $this->assertDatabaseHas('rest_times',[
            'status' => '休憩終了',
            'finish_rest_date' => '2025-01-19',
            'finish_rest_time' => '18:40',
            'total_rest_time' => $totalRestTime,
        ]);
    }
}
