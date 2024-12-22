@extends('layouts/admin-content-app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/attendance-request.css') }}" />
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
    <div class="list__wrapper">
        <table class="list__table">
            <tr class="table__item">
                <th class="state">状態</th>
                <th class="name">名前</th>
                <th class="target-date">対象日時</th>
                <th class="reason">申請理由</th>
                <th class="request-date">申請日時</th>
                <th class="detail">詳細</th>
            </tr>
            <tr class="table__item">
                <td class="state">承認待ち</td>
                <td class="name">西玲奈</td>
                <td class="target-date">2024/12/16</td>
                <td class="reason">遅延のため</td>
                <td class="request-date">2024/12/16</td>
                <td class="detail"><a href="">詳細</a></td>
            </tr>
            <tr class="table__item">
                <td class="state">承認待ち</td>
                <td class="name">西玲奈</td>
                <td class="target-date">2024/12/16</td>
                <td class="reason">遅延のため</td>
                <td class="request-date">2024/12/16</td>
                <td class="detail"><a href="">詳細</a></td>
            </tr>
        </table>
    </div>
</div>
@endsection