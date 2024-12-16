@extends('layouts/content-app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance-detail.css') }}" />
@endsection

@section('content')
<div class="attendance-detail__container">
    <p class="attendance-detail__ttl">勤怠詳細</p>
    <div class="detail__wrapper">
        <table class="detail__table">
            <tr class="table__item">
                <th class="name__ttl">名前</th>
                <td class="name">西 玲奈</td>
            </tr>
            <tr class="table__item">
                <th class="date__ttl">日付</th>
                <td class="date">
                    <input type="text">
                    <input type="text">
                </td>
            </tr>
            <tr class="table__item">
                <th class="work__ttl">出勤・退勤</th>
                <td class="work-time">
                    <input type="text">
                    <input type="text">
                </td>
            </tr>
            <tr class="table__item">
                <th class="rest__ttl">休憩</th>
                <td class="rest-time">
                    <input type="text">
                    <input type="text">
                </td>
            </tr>
            <tr class="table__item">
                <th class="remarks__ttl">備考</th>
                <td class="remarks">
                    <textarea name="" id="">電車遅延のため</textarea>
                </td>
            </tr>
        </table>
    </div>
</div>
@endsection