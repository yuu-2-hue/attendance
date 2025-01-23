<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\WorkTime;
use App\Models\RestTime;
use App\Models\Apply;

class UserDetailControllerTest extends TestCase
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
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /** @test */
    public function AccessDetailView()
    {
        Apply::create([
            'user_id' => 1,
            'work_time_id' => 1,
            'status' => '承認待ち',
            'name' => 'user',
            'target_date' => '2025-01-19',
            'request_date' => '2025-01-20',
            'reason' => '電車遅延のため',
        ]);

        $workId = 1;

        $user = User::Find(1);
        $this->actingAs($user);
        $workTime = WorkTime::Find($workId);
        $applies = Apply::where('work_time_id', $workId)->get();

        $status = '';
        foreach($applies as $apply)
        {
            if($apply->status != null) $status = $apply->status;
        }

        $workData = array(
            'id' => $workId,
            'name' => $user->name,
            'year' => date(('Y年'), strtotime($workTime->at_work_date)),
            'date' => date(('n月j日'), strtotime($workTime->at_work_date)),
            'at_work_time' => substr($workTime->at_work_time, 0, 5),
            'finish_work_time' => substr($workTime->finish_work_time, 0, 5),
        );
        // 比較
        $this->assertEquals($workData['id'], 1);
        $this->assertEquals($workData['name'], 'user');
        $this->assertEquals($workData['year'], '2025年');
        $this->assertEquals($workData['date'], '1月19日');
        $this->assertEquals($workData['at_work_time'], '18:00');
        $this->assertEquals($workData['finish_work_time'], '19:00');

        $restData = RestTime::where('work_time_id', $workId)->get();

        // レスポンス確認
        $response = $this->get(route('user.detail', 1));
        $this->assertTrue(Auth::check());
        $response->assertStatus(200);
        $response->assertViewIs('user.attendance-detail');
        $response->assertSee($status);
    }

    /** @test */
    public function RetouchAddApplyDatabase()
    {
        $workId = 1;
        $user = User::Find(1);
        $this->actingAs($user);
        $workTime = WorkTime::Find($workId);
        $today = new Carbon('2025-01-20');
        $targetDay = Carbon::createFromFormat('Y年m月d日', '2025年01月19日')->format('Y-m-d');

        // 申請データベースに登録
        Apply::create([
            'user_id' => $user->id,
            'work_time_id' => $workId,
            'status' => '承認待ち',
            'name' => $user->name,
            'target_date' => $targetDay,
            'request_date' => $today,
            'reason' => '電車遅延のため',
        ]);
        $this->assertDatabaseHas('applies',[
            'user_id' => 1,
            'work_time_id' => 1,
            'status' => '承認待ち',
            'name' => 'user',
            'target_date' => '2025-01-19',
            'request_date' => '2025-01-20',
            'reason' => '電車遅延のため',
        ]);
    }

    /** @test */
    public function RetouchUpdateRestDatabase()
    {
        $workId = 1;
        $user = User::Find(1);
        $this->actingAs($user);

        $startTime = '18:40';
        $finishTime = '18:50';

        // 休憩時間のデータベースを更新
        $restTimes = RestTime::where('work_time_id', $workId)->get();
        for($i = 0; $i < count($restTimes); $i++)
        {
            $restTimes[$i]->update([
                'at_rest_time' => $startTime,
                'finish_rest_time' => $finishTime,
                'total_rest_time' => RestTime::CalculationTotalRestTime($startTime, $finishTime),
            ]);
        }

        $this->assertDatabaseHas('rest_times',[
            'at_rest_time' => '18:40:00',
            'finish_rest_time' => '18:50:00',
            'total_rest_time' => '00:10',
        ]);
    }

    /** @test */
    public function RetouchUpdateWorkDatabase()
    {
        $workId = 1;
        $user = User::Find(1);
        $this->actingAs($user);

        $startDate = '2025-01-19 18:00';
        $finishDate = '2025-01-19 20:00';

        // 労働時間のデータベースを更新
        $atWorkDate = new Carbon($startDate);
        $finishWorkDate = new Carbon($finishDate);
        WorkTime::Find(1)->update([
            'at_work_date' => Carbon::createFromFormat('Y年m月d日', '2025年01月19日')->format('Y-m-d'),
            'at_work_time' => '18:00',
            'finish_work_time' => '20:00',
            'total_work_time' => WorkTime::GetWorkTime($atWorkDate, $finishWorkDate),
        ]);

        $this->assertDatabaseHas('work_times',[
            'at_work_date' => '2025-01-19',
            'at_work_time' => '18:00',
            'finish_work_time' => '20:00',
            'total_work_time' => WorkTime::GetWorkTime($atWorkDate, $finishWorkDate),
        ]);
    }

}
