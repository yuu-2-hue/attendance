@extends('layouts/admin-content-app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/attendance-approve.css') }}" />
@endsection

@section('content')
<div class="attendance-approve__container">
    <p class="attendance-approve__ttl">勤怠詳細</p>
    <div class="approve__wrapper">
        <form class="approve__form" action="/admin/stamp_correction_request/approve/{{$workId}}" method="post">
            @csrf
            <input type="text" name="id" value="{{$workData['id']}}" hidden>
            <table class="approve__table">
                <tr class="table__item">
                    <th class="name__ttl">名前</th>
                    <td class="name">{{ $workData['name'] }}</td>
                </tr>
                <tr class="table__item">
                    <th class="date__ttl">日付</th>
                    <td class="date">
                        <input class="input__first" name="year" type="text" value="{{ $workData['year'] }}" readonly>
                        <input class="input__second" name="date" type="text" value="{{ $workData['date'] }}" readonly>
                    </td>
                </tr>
                <tr class="table__item">
                    <th class="work__ttl">出勤・退勤</th>
                    <td class="work-time">
                        <input class="input__first" name="at_work_time" type="text" value="{{ $workData['at_work_time'] }}" readonly>
                        <span>～</span>
                        <input class="input__second" name="finish_work_time" type="text" value="{{ $workData['finish_work_time'] }}" readonly>
                    </td>
                </tr>
                @for($i = 0; $i < count($restData); $i++)
                <tr class="table__item">
                    <th class="rest__ttl">休憩{{$i+1}}</th>
                    <td class="rest-time">
                        <input class="input__first" name="at_rest_time[]" type="text" value="{{ substr($restData[$i]->at_rest_time, 0, 5) }}" readonly>
                        <span>～</span>
                        <input class="input__second" name="finish_rest_time[]" type="text" value="{{ substr($restData[$i]->finish_rest_time, 0, 5) }}" readonly>
                    </td>
                </tr>
                @endfor
                <tr class="table__item">
                    <th class="remarks__ttl">備考</th>
                    <td class="remarks">
                        <textarea name="reason" readonly>電車遅延のため</textarea>
                    </td>
                </tr>
            </table>
            @if($status == '承認待ち')
                <button class="no-approve__button" type="submit">承認</button>
            @else
                <button class="approve__button" type="submit">承認済み</button>
            @endif
        </form>
    </div>
</div>
@endsection