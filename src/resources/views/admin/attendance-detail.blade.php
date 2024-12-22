@extends('layouts/admin-content-app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/attendance-detail.css') }}" />
@endsection

@section('content')
<div class="attendance-detail__container">
    <p class="attendance-detail__ttl">勤怠詳細</p>
    <div class="detail__wrapper">
        <form class="detail__form" action="">
            <table class="detail__table">
                <tr class="table__item">
                    <th class="name__ttl">名前</th>
                    <td class="name">西 玲奈</td>
                </tr>
                <tr class="table__item">
                    <th class="date__ttl">日付</th>
                    <td class="date">
                        <input class="input__first" type="text" value="2024年">
                        <input class="input__second" type="text" value="12月17日">
                    </td>
                </tr>
                <tr class="table__item">
                    <th class="work__ttl">出勤・退勤</th>
                    <td class="work-time">
                        <input class="input__first" type="text" value="09:00">
                        <span>～</span>
                        <input class="input__second" type="text" value="18:00">
                    </td>
                </tr>
                <tr class="table__item">
                    <th class="rest__ttl">休憩</th>
                    <td class="rest-time">
                        <input class="input__first" type="text" value="12:00">
                        <span>～</span>
                        <input class="input__second" type="text" value="13:00">
                    </td>
                </tr>
                <tr class="table__item">
                    <th class="rest__ttl">休憩2</th>
                    <td class="rest-time">
                        <input class="input__first" type="text" value="">
                        <span>～</span>
                        <input class="input__second" type="text" value="">
                    </td>
                </tr>
                <tr class="table__item">
                    <th class="remarks__ttl">備考</th>
                    <td class="remarks">
                        <textarea name="" id="">電車遅延のため</textarea>
                    </td>
                </tr>
            </table>
            <button class="retouch__button" type="submit">修正</button>
        </form>
    </div>
</div>
@endsection