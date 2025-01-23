<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use DateTime;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'name' => '松原 隼人',
            'email' => 'matubara@example.com',
            'email_verified_at' => new DateTime(),
            'password' => Hash::make('matubara'),
        ];
        DB::table('users')->insert($param);
        $param = [
            'name' => '川口 大和',
            'email' => 'kawaguti@example.com',
            'email_verified_at' => new DateTime(),
            'password' => Hash::make('kawaguti'),
        ];
        DB::table('users')->insert($param);
        $param = [
            'name' => '渡辺 清香',
            'email' => 'watanabe@example.com',
            'email_verified_at' => new DateTime(),
            'password' => Hash::make('watanabe'),
        ];
        DB::table('users')->insert($param);
        $param = [
            'name' => '早川 鈴野',
            'email' => 'hayakawa@example.com',
            'email_verified_at' => new DateTime(),
            'password' => Hash::make('hayakawa'),
        ];
        DB::table('users')->insert($param);
        $param = [
            'name' => '獅子堂 漣',
            'email' => 'sisidouu@example.com',
            'email_verified_at' => new DateTime(),
            'password' => Hash::make('sisidouu'),
        ];
        DB::table('users')->insert($param);
    }
}
