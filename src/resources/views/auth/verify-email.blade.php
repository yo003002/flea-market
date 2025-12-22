@extends('layouts.app-login')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/verify-email.css') }}">
@endsection

@section('content')
<div class="verify-content">
    <div class="verify-message">
        <h3>
            登録していただいたメールアドレスに認証メールを送付しました。<br>
            メール認証を完了してください。
        </h3>
    </div>
    <form method="post" action="{{ route('verification.send') }}" class="verify-form">
        @csrf
        <button type="submit" class="verify-form__button">
            認証はこちらから
        </button>
    </form>
    <div class="verify-resend">
        <p>認証メールを再送する</p>
    </div>
</div>
@endsection