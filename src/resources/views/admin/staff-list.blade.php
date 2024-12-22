@extends('layouts/admin-content-app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/staff-list.css') }}" />
@endsection

@section('content')
<div class="attendance-list__container">
    <p class="attendance-list__ttl">スタッフ一覧</p>
    <div class="list__wrapper">
        <table class="list__table">
            <tr class="table__item">
                <th class="name">名前</th>
                <th class="mail">メールアドレス</th>
                <th class="monthly-attendance">月次勤怠</th>
            </tr>
            <tr class="table__item">
                <td class="name">山田 太郎</td>
                <td class="mail">example@email.com</td>
                <td class="monthly-attendance"><a href="">詳細</a></td>
            </tr>
            <tr class="table__item">
                <td class="name">西 玲奈</td>
                <td class="mail">example@email.com</td>
                <td class="monthly-attendance"><a href="">詳細</a></td>
            </tr>
        </table>
    </div>
</div>
@endsection