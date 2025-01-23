@extends('layouts/user-content-app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/user/attendance-detail.css') }}" />
@endsection

@section('content')
<div class="attendance-detail__container">
    <p class="attendance-detail__ttl">勤怠詳細</p>
    <div class="detail__wrapper">
        <form class="detail__form" action="/attendance/{{$workData['id']}}/retouch" method="post">
            @csrf
            <table class="detail__table">
                <tr class="table__item">
                    <th class="name__ttl">名前</th>
                    <td class="name">{{ $workData['name'] }}</td>
                </tr>
                <tr class="table__item">
                    <th class="date__ttl">日付</th>
                    <td class="date">
                        @if($status == null)
                        <input class="input__first" name="year" type="text" value="{{ $workData['year'] }}">
                        <input class="input__second" name="date" type="text" value="{{ $workData['date'] }}">
                        @else
                        <input class="input__first" name="year" type="text" value="{{ $workData['year'] }}" readonly>
                        <input class="input__second" name="date" type="text" value="{{ $workData['date'] }}" readonly>
                        @endif
                    </td>
                </tr>
                <tr class="table__item">
                    <th class="work__ttl">出勤・退勤</th>
                    <td class="work-time">
                        @if($status == null)
                        <input class="input__first" name="at_work_time" type="text" value="{{ $workData['at_work_time'] }}">
                        <span>～</span>
                        <input class="input__second" name="finish_work_time" type="text" value="{{ $workData['finish_work_time'] }}">
                        <div class="form__error"> @error('at_work_time') {{ $message }} @enderror </div>
                        <div class="form__error"> @error('finish_work_time') {{ $message }} @enderror </div>
                        @else
                        <input class="input__first" name="at_work_time" type="text" value="{{ $workData['at_work_time'] }}" readonly>
                        <span>～</span>
                        <input class="input__second" name="finish_work_time" type="text" value="{{ $workData['finish_work_time'] }}" readonly>
                        @endif
                    </td>
                </tr>
                @for($i = 0; $i < count($restData); $i++)
                <tr class="table__item">
                    <th class="rest__ttl">休憩{{$i+1}}</th>
                    <td class="rest-time">
                        @if($status == null)
                        <input class="input__first" name="at_rest_time[{{$i}}]" type="text" value="{{ substr($restData[$i]->at_rest_time, 0, 5) }}">
                        <span>～</span>
                        <input class="input__second" name="finish_rest_time[{{$i}}]" type="text" value="{{ substr($restData[$i]->finish_rest_time, 0, 5) }}">
                        <div class="form__error"> @error("at_rest_time.$i") {{ $message }} @enderror </div>
                        <div class="form__error"> @error("finish_rest_time.$i") {{ $message }} @enderror </div>
                        @else
                        <input class="input__first" name="at_rest_time[]" type="text" value="{{ substr($restData[$i]->at_rest_time, 0, 5) }}" readonly>
                        <span>～</span>
                        <input class="input__second" name="finish_rest_time[]" type="text" value="{{ substr($restData[$i]->finish_rest_time, 0, 5) }}" readonly>
                        @endif
                    </td>
                </tr>
                @endfor
                <tr class="table__item">
                    <th class="remarks__ttl">備考</th>
                    <td class="remarks">
                        @if($status == null)
                            <textarea name="reason">電車遅延のため</textarea>
                            <div class="form__error"> @error('reason'){{ $message }}@enderror </div>
                        @else
                            <textarea name="reason" readonly>電車遅延のため</textarea>
                        @endif
                    </td>
                </tr>
            </table>
            @if($status == '承認待ち')
                <p class="no-retouch__text">※承認待ちのため修正できません</p>
            @else
                <button class="retouch__button" type="submit">修正</button>
            @endif
        </form>
    </div>
</div>
@endsection