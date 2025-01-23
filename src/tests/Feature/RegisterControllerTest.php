<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
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
    public function AccessRegisterView()
    {
        $response = $this->get(route('register'));
        $response->assertViewIs('auth.register');
    }

     /** @test */
    public function AfterRegisterAttendanceView()
    {
        // ユーザー登録処理
        $response = $this->post(route('register'), [
            'name' => 'testUser',
            'email' => 'test@example.com',
            'password' => 'registerPass',
            'password_confirmation' => 'registerPass'
        ]);
        $response->assertRedirect(route('user.attendance'));
    }

    /** @test */
    public function CannotRegisterNullName()
    {
        $response = $this->post(route('register'), [
            'name'  => '',
            'email'    => 'test@example.com',
            'password'  => 'password123'
        ]);
        $errorMessage = '名前は必ず指定してください';
        $this->get(route('register'))->assertSee($errorMessage);
    }

    /** @test */
    public function CannotRegisterNullEmail()
    {
        $response = $this->post(route('register'), [
            'name'  => 'testuser',
            'email'    => '',
            'password'  => 'password123'
        ]);
        $errorMessage = 'メールアドレスは必ず指定してください';
        $this->get(route('register'))->assertSee($errorMessage);
    }

    /** @test */
    public function CannotRegisterNullPassword()
    {
        $response = $this->post(route('register'), [
            'name'  => 'testuser',
            'email'    => 'test@example.com',
            'password'  => ''
        ]);
        $errorMessage = 'パスワードには正しい形式を指定してください';
        $this->get(route('register'))->assertSee($errorMessage);
    }
}
