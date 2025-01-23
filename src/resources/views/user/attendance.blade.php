@extends('layouts/user-content-app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/user/attendance.css') }}" />
@endsection

@section('content')
<div class="attendance__container">
    <form class="attendance__form" action="/attendance/store" method="post">
    @csrf
        <div class="form__item">
            <span class="item__status">{{$status}}</span>
            <input class="item__status" name="status" value="{{$status}}" readonly hidden>
        </div>
        <div class="form__item">
            <span class="item__date" id="date"></span>
            <input class="item__date" id="year" name="year" value="" readonly hidden>
            <input class="item__date" id="month" name="month" value="" readonly hidden>
            <input class="item__date" id="day" name="day" value="" readonly hidden>
            <input class="item__date" id="week" name="week" value="" readonly hidden>
        </div>
        <div class="form__item">
            <span class="item__time" id="time"></span>
            <input class="item__time" id="hour" name="hour" value="" readonly hidden>
            <input class="item__time" id="min" name="min" value="" readonly hidden>
            <input class="item__time" id="sec" name="sec" value="" readonly hidden>
        </div>
        @if($status == '勤務外')
            <button class="at-work__button" name="at_work" type="submit">出勤</button>
        @elseif($status == '勤務中')
            <button class="finish-work__button" name="finish_work" type="submit">退勤</button>
            <button class="at-rest__button" name="at_rest" type="submit">休憩入</button>
        @elseif($status == '休憩中')
            <button class="finish-rest__button" name="finish_rest" type="submit">休憩戻</button>
        @else
            <span class="finish-work__text">お疲れ様でした。</span>
        @endif
    </form>
</div>
<script>
    var date = new Date();
    var year = date.getFullYear();
    var mon = date.getMonth()+1;
    var week = date.getDay();
    var day = date.getDate().toString().padStart(2, "0");
    var week_ja= new Array("日","月","火","水","木","金","土");

    document.getElementById("year").value = year;
    document.getElementById("month").value = mon;
    document.getElementById("day").value = day;
    document.getElementById("week").value = week_ja[week];
    document.getElementById("date").innerHTML = year + "年" + mon.toString().padStart(2, "0") + "月" + day + "日" + "(" + week_ja[week] + ")";

    timerID = setInterval('clock()', 500);
    function clock() {
        var date = new Date();
        var hour = date.getHours().toString().padStart(2, "0");
        var min = date.getMinutes().toString().padStart(2, "0");
        var sec = date.getSeconds().toString().padStart(2, "0");
        document.getElementById("hour").value = hour;
        document.getElementById("min").value = min;
        document.getElementById("sec").value = sec;
        document.getElementById("time").innerHTML = hour + ":" + min;
    }

</script>
@endsection