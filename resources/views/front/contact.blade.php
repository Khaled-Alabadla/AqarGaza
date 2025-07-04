@extends('layouts.front')

@section('title', 'تواصل معنا')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/contact_styles.css') }}">
@endpush


@section('content')
    @include('layouts.hero', [
        'title' => 'تواصل معنا',
        'description' => 'نحن دائماٌ جاهزون للرد على أي استفسارات أو أسئلة تقومون بطرحها',
    ])
    <div class="add">

        <div class="contact-form-column">
            <section class="form-section">
                <div class="section-header">
                    <span class="section-number">1</span>
                    <h2>تواصل معنا</h2>
                </div>
                <p class="section-description">نعمل بجد لخدمتك</p>

                <form id="contactForm">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="first-name">الاسم الأول</label>
                            <input type="text" id="first-name" placeholder="الاسم الأول">
                        </div>
                        <div class="form-group">
                            <label for="last-name">الاسم الثاني</label>
                            <input type="text" id="last-name" placeholder="الاسم الثاني">
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="email">البريد الإلكتروني</label>
                            <input type="email" id="email" placeholder="البريد الإلكتروني">
                        </div>
                        <div class="form-group">
                            <label for="phone">الهاتف</label>
                            <input type="text" id="phone" placeholder="الهاتف">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address">العنوان بالتفصيل</label>
                        <input type="text" id="address"
                            placeholder="الفلسطيني - غزة - الشيخ رضوان - شارع الشافعي - فيلا بجانب سوق سيارات القدس">
                    </div>

                    <div class="section-header message-header">
                        <span class="section-number">2</span>
                        <h2>الرسالة</h2>
                    </div>
                    <div class="form-group">
                        <textarea id="message" rows="8" placeholder="الرسالة"></textarea>
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
                <p class="email-address">bilalradwan@gmail.com</p>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src=".{{ asset('assets/js/contact.js') }}"></script>
@endpush
