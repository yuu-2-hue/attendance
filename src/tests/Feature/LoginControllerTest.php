<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class LoginControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /** @test */
    public function AccessLoginView()
    {
        $response = $this->get(route('login'));
        $response->assertViewIs('auth.login');
    }

    /** @test */
    public function LoginEmailAndPassword()
    {
        $user = User::factory()->create([
            'email' => 'pass@example.com',
            'password'  => bcrypt('loginPass')
        ]);
        // ログイン処理
        $response = $this->post(route('login'), [
            'email'    => 'pass@example.com',
            'password'  => 'loginPass'
        ]);
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function AfterLoginAttendanceView()
    {
        $user = User::factory()->create([
            'email' => 'pass@example.com',
            'password'  => bcrypt('loginPass')
        ]);
        // ログイン処理
        $response = $this->post(route('login'), [
            'email'    => 'pass@example.com',
            'password'  => 'loginPass'
        ]);
        $response->assertRedirect(route('user.attendance'));
    }

    /** @test */
    public function CannotLoginDifferentEmailAndDifferentPassword()
    {
        $user = User::factory()->create([
            'email' => 'pass@example.com',
            'password'  => bcrypt('loginPass')
        ]);
        // ログイン処理
        $response = $this->post(route('login'), [
            'email'    => 'pass@exae.com',
            'password'  => 'loginPass'
        ]);
        $this->assertGuest();
    }

    /** @test */
    public function CannotLoginDifferentPassword()
    {
        $user = User::factory()->create([
            'email' => 'pass@example.com',
            'password'  => bcrypt('loginpass')
        ]);
        // ログイン処理
        $response = $this->post(route('login'), [
            'email'    => 'pass@example.com',
            'password'  => 'liginpass'
        ]);
        $this->assertGuest();
    }

    /** @test */
    public function CannotLoginDifferentEmailMessage()
    {
        $user = User::factory()->create([
            'email' => 'pass@example.com',
            'password'  => bcrypt('loginPass')
        ]);
        $response = $this->post(route('login'), [
            'email'    => 'pass@exae.com',
            'password'  => 'loginPass'
        ]);
        $errorMessage = 'メールアドレスまたはパスワードが間違っています';
        $this->get(route('login'))->assertSee($errorMessage);
    }

    /** @test */
    public function CannotLoginDifferentPasswordMessage()
    {
        $user = User::factory()->create([
            'email' => 'pass@example.com',
            'password'  => bcrypt('loginpass')
        ]);
        $response = $this->post(route('login'), [
            'email'    => 'pass@example.com',
            'password'  => 'lss'
        ]);
        $errorMessage = 'メールアドレスまたはパスワードが間違っています';
        $this->get(route('login'))->assertSee($errorMessage);
    }

    /** @test */
    public function Logout()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        // ログアウトリクエスト
        $response = $this->post(route('logout'));
        // ユーザーが認証されていない
        $this->assertGuest();
    }

    /** @test */
    public function AfterLogoutLoginView()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        // ログアウトリクエスト
        $response = $this->post(route('logout'));
        $response->assertRedirect(route('login'));
    }
}
