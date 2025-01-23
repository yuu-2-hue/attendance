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

class UserListControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh');

        $user = User::factory()->create([
            'name' => 'user',
            'email' => 'emails@example.com',
            'password'  => bcrypt('password'),
        ]);
        WorkTime::create([
            'user_id' => 1,
            'status' => '退勤済み',
            'at_work_date' => '2025-01-19',
            'finish_work_date' => '2025-01-19',
            'at_work_time' => '18:00:00',
            'finish_work_time' => '19:00:00',
            'total_work_time' => '01:00:00',
        ]);
        RestTime::create([
            'work_time_id' => 1,
            'status' => '休憩終了',
            'at_rest_date' => '2025-01-19',
            'finish_rest_date' => '2025-01-19',
            'at_rest_time' => '18:30:00',
            'finish_rest_time' => '18:40:00',
            'total_rest_time' => '00:10:00',
        ]);
        $user = User::Find(1);
        $this->actingAs($user);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /** @test */
    public function AccessAttendanceListView()
    {
        $value = 0;
        $workTimes = WorkTime::GetThisMonthAttendance($value);

        $targetMonth = new Carbon($value.' months');
        $month = $targetMonth->format('Y年m月');
        $this->assertEquals($month, '2025年01月');

        Carbon::setLocale('ja');

        $lists = [];
        foreach($workTimes as $workTime)
        {
            if($workTime->user_id == 1)
            {
                $atWorkTime = substr($workTime->at_work_time, 0, 5);
                $finishWorkTime = substr($workTime->finish_work_time, 0, 5);
                $carbon = new Carbon($workTime->at_work_date);
                $date = $carbon->isoFormat('MM/DD (ddd)');
                $restTime = RestTime::GetTotalRestTime($workTime->id);
                if($workTime->total_work_time != null) $totalWorkTime = WorkTime::GetTotalWorkTime($workTime->total_work_time, $restTime);
                else $totalWorkTime = '';
                // 比較
                $this->assertEquals($workTime->id, 1);
                $this->assertEquals($date, '01/19 (日)');
                $this->assertEquals($atWorkTime, '18:00');
                $this->assertEquals($finishWorkTime, '19:00');
                $this->assertEquals($restTime, '00:10');
                $this->assertEquals($totalWorkTime, '00:50');
            }
        }

        // レスポンス確認
        $response = $this->get(route('user.list'));
        $this->assertTrue(Auth::check());
        $response->assertStatus(200);
        $response->assertViewIs('user.attendance-list');
        $response->assertSee($month);
    }

    /** @test */
    public function PushPreviousMonthButton()
    {
        $value = 0;
        $value -= 1;
        $this->assertEquals($value, -1);

        // レスポンス確認
        $response = $this->get(route('user.list'));
        $this->assertTrue(Auth::check());
        $response->assertStatus(200);
        $response->assertViewIs('user.attendance-list');
    }

    /** @test */
    public function PushNextMonthButton()
    {
        $value = 0;
        $value += 1;
        $this->assertEquals($value, 1);

        // レスポンス確認
        $response = $this->get(route('user.list'));
        $this->assertTrue(Auth::check());
        $response->assertStatus(200);
        $response->assertViewIs('user.attendance-list');
    }
}
