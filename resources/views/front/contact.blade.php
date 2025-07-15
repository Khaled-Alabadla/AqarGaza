@extends('layouts.front')

@section('title', 'تواصل معنا')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/contact_styles.css') }}">
@endpush


@section('content')
    @include('layouts.hero', [
        'title' => $page->title,
        'description' => $page->subtitle,
    ])
    <div class="add">

        <div class="contact-form-column">
            <section class="form-section">
                <div class="section-header">
                    <span class="section-number">1</span>
                    <h2>تواصل معنا</h2>
                </div>
                <p class="section-description">نعمل بجد لخدمتك</p>

                <form id="contactForm" action="{{ route('front.contact') }}" method="POST">
                    @csrf
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="name">الاسم</label>
                            <input type="text" id="name" placeholder="الاسم" name="name">
                        </div>
                        <div class="form-group">
                            <label for="email">البريد الإلكتروني</label>
                            <input type="email" id="email" placeholder="البريد الإلكتروني" name="email">
                        </div>
                        <div class="form-group">
                            <label for="phone">رقم الجوال</label>
                            <input type="text" id="phone" placeholder="رقم الجوال" name="phone">
                        </div>

                    </div>


                    <div class="section-header message-header">
                        <span class="section-number">2</span>
                        <h2>الرسالة</h2>
                    </div>
                    <div class="form-group">
                        <textarea id="message" name="message" rows="8" placeholder="الرسالة"></textarea>
                    </div>

                    <button type="submit" class="submit-btn">إرسال</button>
                </form>
            </section>
        </div>
        <div class="contact-info-column">
            <div class="info-card whatsapp-card">
                <i class="fab fa-whatsapp"></i>
                <h3>معلومات التواصل عبر الواتساب</h3>
                <p class="phone-number">+970599441544</p>
            </div>

            <div class="info-card email-card">
                <i class="far fa-envelope"></i>
                <h3>معلومات التواصل عبر البريد الإلكتروني</h3>
                <p class="email-address">aqar-gaza@gmail.com</p>
            </div>
        </div>
    </div>
@endsection
