@extends('layouts.front')

@section('title')
    {{ $property->title }}
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/property_details_styles.css') }}">
@endpush

<button class="chat-fab-simple" aria-label="الدردشات">
    <i class="fas fa-comment-dots"></i>
</button>

<aside class="chat-sidebar-simple" aria-hidden="true">
    <div class="chat-sidebar-header-simple">
        <h3>الدردشات</h3>
        <button class="chat-close-btn-simple" aria-label="إغلاق الدردشات">
            <i class="fas fa-xmark"></i>
        </button>
    </div>
    <ul class="chat-list-simple">
    </ul>
    <div class="chat-sidebar-footer-simple">
        <a href="chats.html" class="all-chats-btn-simple">كل الدردشات</a>
    </div>
</aside>

@section('content')
    <div class="page-header">
        <h1>شقة مفروشة بالقرب من سوبر ماركت العبادلة - خانيونس</h1>
    </div>

    <div class="add">

        <div class="property-main-column">
            <section class="property-overview card">
                <div class="overview-header">
                    <i class="fas fa-check-circle property-type-icon"></i>
                    <h2>شقة</h2>
                </div>
                <div class="property-address-info">
                    <p class="location-details"><i class="fas fa-map-marker-alt"></i> فلسطين - غزة - خانيونس</p>
                    <p class="detailed-location">غزة - خانيونس - الرمال - شارع العباسي - بالقرب من سوبر ماركت العبادلة</p>
                    <p class="area-details"><i class="fas fa-ruler-combined"></i> المساحة: 180 م²</p>
                    <p class="price-details"><i class="fas fa-dollar-sign"></i> 1,250.00$</p>
                </div>
                <button class="btn btn-more-details">
                    <i class="fas fa-info-circle"></i>
                    المزيد
                </button>
            </section>

            <section class="other-details card">
                <h2>تفاصيل أخرى</h2>
                <div class="details-grid">
                    <div><i class="fas fa-bed"></i> 3 غرف نوم</div>
                    <div><i class="fas fa-bath"></i> 2 حمامات</div>
                    <div><i class="fas fa-couch"></i> 2 صالون</div>
                    <div><i class="fas fa-building"></i> الطابق الثالث</div>
                    <div><i class="fa-solid fa-house-chimney"></i> بلكونة</div>
                    <div><i class="fas fa-elevator"></i> مصعد</div>
                    <div><i class="fas fa-parking"></i> موقف سيارات</div>
                    <div><i class="fas fa-utensils"></i> مطبخ</div>
                </div>
            </section>

            <section class="about-property card">
                <h2>عن العقار</h2>
                <p>شقة، مفروشة، للإيجار، بالجيش، بالقرب من سوبر ماركت العبادلة، منطقة حيوية، يوجد بها، مصعد، غرفتين نوم،
                    صالون، مطبخ، حمامين، يوجد بها عداد مياه، عداد كهرباء، غاز، انترنت، تلفون، مولد كهرباء، تدفئة مركزية،
                    إطلالة جميلة على الطبيعة.
                    البرج الأفضل.</p>
            </section>
            <section class="main-hero-property-image card">
                <h2>الصورة الرئيسية</h2>
                <div class="main-hero-image-container">
                    <img src="{{ asset('assets/img/account_settings.png') }}" alt="صورة العقار الرئيسية">
                </div>
            </section>

            <section class="property-images card">
                <h2>صور إضافية</h2>
                <div class="main-image-container">
                    <img id="main-property-img" src="{{ asset('assets/img/landing.jpg') }}" alt="Property Main Image">
                </div>
                <div class="thumbnails-container">
                    <img class="thumbnail active" src="{{ asset('assets/img/landing.jpg') }}" alt="Thumbnail 1"
                        data-full-src="{{ asset('assets/img/landing.jpg') }}">
                    <img class="thumbnail" src="{{ asset('assets/img/account_settings.png') }}" alt="Thumbnail 2"
                        data-full-src="{{ asset('assets/img/account_settings.png') }}">
                    <img class="thumbnail" src="{{ asset('assets/img/ho.png') }}" alt="Thumbnail 3"
                        data-full-src="{{ asset('assets/img/ho.png') }}">
                    <img class="thumbnail" src="{{ asset('assets/img/landing.jpg') }}" alt="Thumbnail 4"
                        data-full-src="{{ asset('assets/img/landing.jpg') }}">
                </div>
            </section>

            <section class="comment-section card">
                <h2>اترك تعليقاً أو استفساراً</h2>
                <textarea placeholder="اكتب تعليقاً أو استفساراً هنا"></textarea>
                <button class="btn btn-send-comment">إرسال
                    <i class="fas fa-paper-plane send"></i>
                </button>
            </section>
        </div>

        <div class="side-info-column">
            <section class="owner-info card">
                <h2>صاحب ملك العقار</h2>
                <div class="owner-details">
                    <div class="owner-avatar"><i class="fas fa-user"></i></div>
                    <p class="owner-name">بلال رضوان حماد شقورة</p>
                    <p class="owner-address"><i class="fas fa-map-marker-alt"></i> فلسطين - غزة - خانيونس - حي الأمل - بئر
                        خمسة</p>
                    <p class="owner-phone"><i class="fas fa-phone-alt"></i> +970599441544</p>
                </div>
                <button class="btn btn-contact-owner">تواصل مع المالك</button>
            </section>

            <section class="similar-properties">
                <h2>عقارات أخرى مشابهة لهذه الأوصاف</h2>
                <div class="similar-grid">
                    <div class="property-card">
                        <div class="property-image">
                            <img src="{{ asset('assets/img/landing.jpg') }}" alt="Similar Property Image">
                            <button class="favorite-btn"><i class="far fa-heart"></i></button>
                        </div>
                        <div class="property-info">
                            <span class="property-price">1,250.00$</span>
                            <p class="property-location">القدس، فلسطين</p>
                            <div class="property-features">
                                <div><i class="fas fa-bed"></i> <span>3 غرف</span></div>
                                <div><i class="fas fa-bath"></i> <span>2 حمام</span></div>
                                <div><i class="fas fa-ruler-combined"></i> <span>150 م²</span></div>
                            </div>
                            <p class="property-status">مستقل بموقع بناء خاص بي</p>
                        </div>
                    </div>
                    <div class="property-card">
                        <div class="property-image">
                            <img src="{{ asset('assets/img/landing.jpg') }}" alt="Similar Property Image">
                            <button class="favorite-btn"><i class="far fa-heart"></i></button>
                        </div>
                        <div class="property-info">
                            <span class="property-price">980.00$</span>
                            <p class="property-location">غزة، فلسطين</p>
                            <div class="property-features">
                                <div><i class="fas fa-bed"></i> <span>2 غرف</span></div>
                                <div><i class="fas fa-bath"></i> <span>1 حمام</span></div>
                                <div><i class="fas fa-ruler-combined"></i> <span>120 م²</span></div>
                            </div>
                            <p class="property-status">شقة سكنية عصرية</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/property_details.js') }}"></script>
@endpush
