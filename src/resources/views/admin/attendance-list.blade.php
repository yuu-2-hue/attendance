@extends('layouts/admin-content-app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/attendance-list.css') }}" />
@endsection

@section('content')
<div class="attendance-list__container">
    <p class="attendance-list__ttl">勤怠一覧</p>
    <div class="calendar__wrapper">
        <form class="calendar__form" action="">
            <button class="previous-day__button" name="previous-day">
                <img src="{{ asset('img/left-arrow.svg') }}" alt="">
                <span>前日</span>
            </button>
            <div class="date__item">
                <img src="{{ asset('img/calendar-icon.png') }}" alt="">
                <span>2024/12/01</span>
            </div>
            <button class="next-day__button" name="next-day">
                <span>翌日</span>
                <img src="{{ asset('img/right-arrow.svg') }}" alt="">
            </button>
        </form>
    </div>
    <div class="list__wrapper">
        <table class="list__table">
            <tr class="table__item">
                <th class="name">名前</th>
                <th class="at-work">出勤</th>
                <th class="finish-work">退勤</th>
                <th class="rest">休憩</th>
                <th class="total">合計</th>
                <th class="detail">詳細</th>
            </tr>
            <tr class="table__item">
                <td class="name">山田 太郎</td>
                <td class="at-work">09:00</td>
                <td class="finish-work">18:00</td>
                <td class="rest">01:00</td>
                <td class="total">08:00</td>
                <td class="detail"><a href="">詳細</a></td>
            </tr>
            <tr class="table__item">
                <td class="name">西 玲奈</td>
                <td class="at-work">09:00</td>
                <td class="finish-work">18:00</td>
                <td class="rest">01:00</td>
                <td class="total">08:00</td>
                <td class="detail"><a href="">詳細</a></td>
            </tr>
        </table>
    </div>
</div>
@endsection