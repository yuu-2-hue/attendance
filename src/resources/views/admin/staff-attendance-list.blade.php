@extends('layouts/admin-content-app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/staff-attendance-list.css') }}" />
@endsection

@section('content')
<div class="staff-attendance-list__container">
    <p class="staff-attendance-list__ttl">{{ $name }}さんの勤怠</p>
    <div class="calendar__wrapper">
        <form class="calendar__form" action="/admin/attendance/staff/{{ $userId }}/changeMonth" method="get">
            <button class="previous-month__button" name="previous-month">
                <img src="{{ asset('img/left-arrow.svg') }}" alt="">
                <span>前月</span>
            </button>
            <div class="date__item">
                <img src="{{ asset('img/calendar-icon.png') }}" alt="">
                <span>{{ $month }}</span>
            </div>
            <button class="next-month__button" name="next-month">
                <span>翌月</span>
                <img src="{{ asset('img/right-arrow.svg') }}" alt="">
            </button>
        </form>
    </div>
    <div class="staff-attendance-list__wrapper">
        <table class="staff-attendance-list__table">
            <tr class="table__item">
                <th class="date">日付</th>
                <th class="at-work">出勤</th>
                <th class="finish-work">退勤</th>
                <th class="rest">休憩</th>
                <th class="total">合計</th>
                <th class="detail">詳細</th>
            </tr>
            @foreach($lists as $list)
                <tr class="table__item">
                    <td class="date">{{ $list['date'] }}</td>
                    <td class="at-work">{{ $list['at_work_time'] }}</td>
                    <td class="finish-work">{{ $list['finish_work_time'] }}</td>
                    <td class="rest">{{ $list['rest_time'] }}</td>
                    <td class="total">{{ $list['total_work_time'] }}</td>
                    <td class="detail"><a href="/admin/attendance/{{ $list['id'] }}">詳細</a></td>
                </tr>
            @endforeach
        </table>
        <form action="{{ '/admin/export?'.http_build_query(request()->query()) }}" method="post">
        @csrf
            <input type="text" name="userId" value="{{ $userId }}" hidden>
            <input class="export__btn" type="submit" value="CSV出力">
        </form>
    </div>
</div>
@endsection