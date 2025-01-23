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

class UserRequestControllerTest extends TestCase
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
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /** @test */
    public function AccessRequestView()
    {
        $user = User::Find(1);
        $this->actingAs($user);

        Apply::create([
            'user_id' => 1,
            'work_time_id' => 1,
            'status' => '承認済み',
            'name' => 'user',
            'target_date' => '2025-01-19',
            'request_date' => '2025-01-20',
            'reason' => '電車遅延のため',
        ]);

        $applies = Apply::where('user_id', 1)->get();

        $tab = 'finished';
        $this->assertEquals($tab, 'finished');

        $lists = [];
        foreach($applies as $apply)
        {
            if($apply->status == '承認済み' && $tab == 'finished')
            {
                array_push($lists, array(
                    'user_id' => $apply->user_id,
                    'work_time_id' => $apply->work_time_id,
                    'status' => $apply->status,
                    'name' => $apply->name,
                    'target_date' => date(('Y/m/d'), strtotime($apply->target_date)),
                    'request_date' => date(('Y/m/d'), strtotime($apply->request_date)),
                    'reason' => $apply->reason,
                ));
            }
        }
        foreach($lists as $list)
        {
            $this->assertEquals($list['status'], '承認済み');
            $this->assertEquals($list['name'], 'user');
            $this->assertEquals($list['target_date'], '2025/01/19');
            $this->assertEquals($list['reason'], '電車遅延のため');
            $this->assertEquals($list['request_date'], '2025/01/20');
        }

        // レスポンス確認
        $response = $this->get(route('user.request'));
        $this->assertTrue(Auth::check());
        $response->assertStatus(200);
        $response->assertViewIs('user.attendance-request');
        $response->assertSee($tab);
        foreach($lists as $list)
        {
            $response->assertSee($list['status']);
            $response->assertSee($list['name']);
            $response->assertSee($list['target_date']);
            $response->assertSee($list['reason']);
            $response->assertSee($list['request_date']);
        }
    }
}
