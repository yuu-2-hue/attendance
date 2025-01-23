@extends('layouts/user-content-app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/user/attendance-request.css') }}" />
@endsection

@section('content')
<div class="attendance-request__container">
    <p class="attendance-request__ttl">申請一覧</p>
    <div class="tab__wrapper">
        <form class="tab__form" action="">
            <button name="tab" value="unfinished" @class(['tab--bold' => $tab=="unfinished", 'tab--black' => $tab=="finished",])>承認待ち</button>
            <button name="tab" value="finished" @class(['tab--bold' => $tab=="finished", 'tab--black' => $tab=="unfinished",])>承認済み</button>
        </form>
    </div>
    <div class="request-list__wrapper">
        <table class="request-list__table">
            <tr class="table__item">
                <th class="state">状態</th>
                <th class="name">名前</th>
                <th class="target-date">対象日時</th>
                <th class="reason">申請理由</th>
                <th class="request-date">申請日時</th>
                <th class="detail">詳細</th>
            </tr>
            @foreach($lists as $list)
                <tr class="table__item">
                    <td class="state">{{ $list['status'] }}</td>
                    <td class="name">{{ $list['name'] }}</td>
                    <td class="target-date">{{ $list['target_date'] }}</td>
                    <td class="reason">{{ $list['reason'] }}</td>
                    <td class="request-date">{{ $list['request_date'] }}</td>
                    <td class="detail">
                        <a href="/attendance/{{ $list['work_time_id'] }}">詳細</a>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection