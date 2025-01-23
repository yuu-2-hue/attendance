@extends('layouts/admin-content-app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/attendance-list.css') }}" />
@endsection

@section('content')
<div class="attendance-list__container">
    <p class="attendance-list__ttl">勤怠一覧</p>
    <div class="calendar__wrapper">
        <form class="calendar__form" action="/admin/attendance/list/change_day" method="post">
        @csrf
            <button class="previous-day__button" name="previous-day">
                <img src="{{ asset('img/left-arrow.svg') }}" alt="">
                <span>前日</span>
            </button>
            <div class="date__item">
                <img src="{{ asset('img/calendar-icon.png') }}" alt="">
                <span>{{$day}}</span>
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
            @foreach($lists as $list)
                <tr class="table__item">
                    <td class="name">{{$list['name']}}</td>
                    <td class="at-work">{{$list['at_work_time']}}</td>
                    <td class="finish-work">{{$list['finish_work_time']}}</td>
                    <td class="rest">{{$list['rest_time']}}</td>
                    <td class="total">{{$list['total_work_time']}}</td>
                    <td class="detail"><a href="/admin/attendance/{{ $list['id'] }}">詳細</a></td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection