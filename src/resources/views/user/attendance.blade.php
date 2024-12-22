@extends('layouts/user-content-app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/user/attendance.css') }}" />
@endsection

@section('content')
<div class="attendance__container">
    <form class="attendance__form" action="" get="post">
    @csrf
    <div class="form__item">
        <span class="item__status">勤怠外</span>
    </div>
    <div class="form__item">
        <span class="item__date" id="current-date"></span>
    </div>
    <div class="form__item">
        <span class="item__time" id="current-time"></span>
    </div>
    <button class="form__button">出勤</button>
    </form>
</div>
<script>
    document.getElementById("current-date").innerHTML = getCurrentDate();

    timerID = setInterval('clock()', 500);
    function clock() {
        document.getElementById("current-time").innerHTML = getCurrentTime();
    }

    function getCurrentDate() {
        var date = new Date();
        var year = date.getFullYear();
        var mon = date.getMonth()+1;
        var week = date.getDay();
        var day = date.getDate().toString().padStart(2, "0");
        var week_ja= new Array("日","月","火","水","木","金","土");

        return year + "年" + mon.toString().padStart(2, "0") + "月" + day + "日" + "(" + week_ja[week] + ")";
    }

    function getCurrentTime() {
        var date = new Date();
        var hour = date.getHours().toString().padStart(2, "0");
        var min = date.getMinutes().toString().padStart(2, "0");

        return hour + ":" + min;
    }
</script>
@endsection