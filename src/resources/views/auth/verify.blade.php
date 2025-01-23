@extends('layouts/auth-app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/verify.css') }}">
@endsection

@section('content')
<div class="verify__container">
    <div class="verify__item">
        <p>ご登録いただいたメールアドレスに確認用のリンクをお送りしました。</p>
        <p>下記ボタンよりメールの確認をお願いします。</p>
        <a href="https://mailtrap.io/inboxes">メールを確認する</a>
    </div>
</div>
@endsection