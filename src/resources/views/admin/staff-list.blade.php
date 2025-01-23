@extends('layouts/admin-content-app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/staff-list.css') }}" />
@endsection

@section('content')
<div class="staff-list__container">
    <p class="staff-list__ttl">スタッフ一覧</p>
    <div class="staff-list__wrapper">
        <table class="staff-list__table">
            <tr class="table__item">
                <th class="name">名前</th>
                <th class="mail">メールアドレス</th>
                <th class="monthly-attendance">月次勤怠</th>
            </tr>
            @foreach($users as $user)
                <tr class="table__item">
                    <td class="name">{{ $user->name }}</td>
                    <td class="mail">{{ $user->email }}</td>
                    <td class="monthly-attendance">
                        <a href="/admin/attendance/staff/{{ $user->id }}">詳細</a>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection