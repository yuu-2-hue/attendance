@extends('layouts/user-content-app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/user/attendance-list.css') }}" />
@endsection

@section('content')
<div class="attendance-list__container">
    <p class="attendance-list__ttl">勤怠一覧</p>
    <div class="calendar__wrapper">
        <form class="calendar__form" action="">
            <button class="previous-month__button" name="previous-month">
                <img src="{{ asset('img/left-arrow.svg') }}" alt="">
                <span>前月</span>
            </button>
            <div class="date__item">
                <img src="{{ asset('img/calendar-icon.png') }}" alt="">
                <span>2024年12月</span>
            </div>
            <button class="next-month__button" name="next-month">
                <span>翌月</span>
                <img src="{{ asset('img/right-arrow.svg') }}" alt="">
            </button>
        </form>
    </div>
    <div class="list__wrapper">
        <table class="list__table">
            <tr class="table__item">
                <th class="date">日付</th>
                <th class="at-work">出勤</th>
                <th class="finish-work">退勤</th>
                <th class="rest">休憩</th>
                <th class="total">合計</th>
                <th class="detail">詳細</th>
            </tr>
            <tr class="table__item">
                <td class="date">06/01(木)</td>
                <td class="at-work">09:00</td>
                <td class="finish-work">18:00</td>
                <td class="rest">01:00</td>
                <td class="total">08:00</td>
                <td class="detail">
                    <form action="">
                        <button type="submit">詳細</button>
                    </form>
                </td>
            </tr>
            <tr class="table__item">
                <td>06/01(木)</td>
                <td>09:00</td>
                <td>18:00</td>
                <td>01:00</td>
                <td>08:00</td>
                <td>
                    <form action="">
                        <button type="submit">詳細</button>
                    </form>
                </td>
            </tr>
        </table>
    </div>
</div>
@endsection