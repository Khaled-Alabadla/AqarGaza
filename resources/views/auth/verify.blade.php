@extends('layouts.front')

@section('title', 'التأكد من البريد الإلكتروني')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/verify_styles.css') }}">
@endpush

@section('content')
    <div class="verify">
        <div class="verify-email-container">
            <div class="verify-email-card">
                <div style="color: rgb(59, 150, 59); margin-bottom: 10px; font-weight:600">
                    {{ $status }}
                </div>

                <div class="mail-icon-wrapper">
                    <i class="fas fa-envelope"></i>
                </div>
                <h1 class="title">التأكد من البريد الإلكتروني</h1>
                <p class="description">من فضلك تأكد من بريدك الإلكتروني من خلال الضغط على الرابط الذي تم إرساله</p>
                <form action="email/verification-notification" method="POST">
                    @csrf
                    <button class="resend-btn">إرسال مرة أخرى</button>
                </form>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button style="border: none;cursor: pointer;" class="logout-link">تسجيل الخروج</button>
                </form>
            </div>
        </div>
    </div>
@endsection
