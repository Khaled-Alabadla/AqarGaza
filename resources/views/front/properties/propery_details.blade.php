@extends('layouts.front')

@section('title')
    {{ $property->title }}
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/property_details_styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/favorite_styles.css') }}">
@endpush


@section('content')
    @include('front.components.favorites')

    @include('front.components.chat')

    <div class="page-header">
        <h1>{{ $property->title }}</h1>
    </div>

    <div class="add">
        <div class="property-main-column">
            <section class="property-overview card">
                <div class="overview-header">
                    <i class="fas fa-check-circle property-type-icon"></i>
                    <h2>{{ $property->category->name }}</h2>
                </div>
                <div class="property-address-info">
                    <p class="location-details"><i class="fas fa-map-marker-alt"></i> {{ $property->city->name }} -
                        {{ $property->zone->name }}</p>
                    <p class="detailed-location">{{ $property->location }}</p>
                    <p class="area-details"><i class="fas fa-ruler-combined"></i> المساحة: {{ $property->area }} م²</p>
                    <p class="price-details"><i class="fas fa-dollar-sign"></i> {{ number_format($property->price, 2) }}
                        @if ($property->currency == 'USD')
                            <span class="currency">دولار</span>
                        @elseif ($property->currency == 'ILS')
                            <span class="currency">شيكل</span>
                        @elseif ($property->currency == 'JOD')
                            <span class="currency">دينار</span>
                        @else
                        @endif
                    </p>
                </div>

            </section>

            <section class="other-details card">
                <h2>تفاصيل أخرى</h2>
                <div class="details-grid">
                    @if ($property->category->name == 'منزل' || $property->category->name == 'شقة' || $property->category->name == 'شاليه')
                        <div><i class="fas fa-bed"></i> {{ $property->rooms }} غرف</div>
                        <div><i class="fas fa-bath"></i> {{ $property->bathrooms }} حمامات</div>
                    @endif
                    {{-- <div><i class="fas fa-couch"></i> {{ $property->salons ?? 'غير محدد' }} صالون</div> --}}
                    {{-- <div><i class="fas fa-building"></i> الطابق {{ $property->floor ?? 'غير محدد' }}</div> --}}
                    {{-- <div><i class="fa-solid fa-house-chimney"></i> {{ $property->has_balcony ? 'بلكونة' : 'لا بلكونة' }}
                    </div> --}}
                    {{-- <div><i class="fas fa-elevator"></i> {{ $property->has_elevator ? 'مصعد' : 'لا مصعد' }}</div> --}}
                    {{-- <div><i class="fas fa-parking"></i> {{ $property->has_parking ? 'موقف سيارات' : 'لا موقف سيارات' }}
                    </div> --}}
                    {{-- <div><i class="fas fa-utensils"></i> {{ $property->has_kitchen ? 'مطبخ' : 'لا مطبخ' }}</div> --}}
                </div>
            </section>

            <section class="about-property card">
                <h2>عن العقار</h2>
                <p>{{ $property->description }}</p>
            </section>

            <section class="main-hero-property-image card">
                <h2>الصورة الرئيسية</h2>
                <div class="main-hero-image-container">
                    <img src="{{ asset('uploads/' . $property->main_image) }}" alt="صورة العقار الرئيسية">
                </div>
            </section>

            <section class="property-images card">
                <h2>صور إضافية</h2>
                <div class="main-image-container">
                    <img id="main-property-img"
                        src="{{ $property->images->first() ? asset('uploads/' . $property->images->first()->image_path) : '' }}"
                        alt="Property Main Image">
                </div>
                <div class="thumbnails-container">
                    @foreach ($property->images as $image)
                        <img class="thumbnail {{ $loop->first ? 'active' : '' }}"
                            src="{{ asset('uploads/' . $image->image_path) }}" alt="Thumbnail {{ $loop->iteration }}"
                            data-full-src="{{ asset('uploads/' . $image->image_path) }}">
                    @endforeach
                </div>
            </section>

            {{-- <section class="comment-section card">
                <h2>اترك تعليقاً أو استفساراً</ “[Your comment or inquiry]”>
                    <form action="{{ route('front.comments.store', $property->id) }}" method="POST">
                        @csrf
                        <textarea name="comment" placeholder="اكتب تعليقاً أو استفساراً هنا" required></textarea>
                        @error('comment')
                            <span class="error">{{ $message }}</span>
                        @enderror
                        <button type="submit" class="btn btn-send-comment">إرسال
                            <i class="fas fa-paper-plane send"></i>
                        </button>
                    </form>
            </section> --}}
        </div>

        <div class="side-info-column">
            <section class="owner-info card">
                <h2>صاحب ملك العقار</h2>
                <div class="owner-details">
                    <div class="owner-avatar">
                        @if ($property->user->image)
                            <img src="{{ asset('uploads/' . $property->user->image) }}" alt="">
                        @else
                            <i class="fas fa-user"></i>
                        @endif
                    </div>
                    <p class="owner-name">{{ $property->user->name }}</p>
                    @if ($property->user->address)
                        <p class="owner-address"><i class="fas fa-map-marker-alt"></i>{{ $property->user->address }}</p>
                    @endif
                    @if ($property->user->phone)
                        <p class="owner-phone"><i class="fas fa-phone-alt"></i>{{ $property->user->phone }}</p>
                    @endif
                </div>
                <button class="btn btn-contact-owner">تواصل مع المالك</button>
            </section>

            {{-- <section class="similar-properties">
                <h2>عقارات أخرى مشابهة</h2>
                <div class="similar-grid">
                    @foreach ($similarProperties as $similarProperty)
                        <div class="property-card">
                            <div class="property-image">
                                <img src="{{ $similarProperty->main_image ? asset('storage/' . $similarProperty->main_image) : asset('assets/img/default_property.png') }}"
                                    alt="Similar Property Image">
                                <button class="favorite-btn" data-property-id="{{ $similarProperty->id }}"><i
                                        class="far fa-heart"></i></button>
                            </div>
                            <div class="property-info">
                                <span class="property-price">{{ number_format($similarProperty->price, 2) }}
                                    {{ $similarProperty->currency }}</span>
                                <p class="property-location">{{ $similarProperty->city->name }}،
                                    {{ $similarProperty->zone->name }}</p>
                                <div class="property-features">
                                    <div><i class="fas fa-bed"></i> <span>{{ $similarProperty->rooms ?? 'غير محدد' }}
                                            غرف</span></div>
                                    <div><i class="fas fa-bath"></i> <span>{{ $similarProperty->bathrooms ?? 'غير محدد' }}
                                            حمام</span></div>
                                    <div><i class="fas fa-ruler-combined"></i> <span>{{ $similarProperty->area }} م²</span>
                                    </div>
                                </div>
                                <p class="property-status">{{ $similarProperty->title }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section> --}}
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/property_details.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Thumbnail click handler for image gallery
            const thumbnails = document.querySelectorAll('.thumbnail');
            const mainImage = document.getElementById('main-property-img');
            thumbnails.forEach(thumbnail => {
                thumbnail.addEventListener('click', function() {
                    thumbnails.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    mainImage.src = this.dataset.fullSrc;
                });
            });



            // Chat sidebar toggle
            const chatFab = document.querySelector('.chat-fab-simple');
            const chatSidebar = document.querySelector('.chat-sidebar-simple');
            const closeBtn = document.querySelector('.chat-close-btn-simple');
            chatFab.addEventListener('click', () => {
                chatSidebar.setAttribute('aria-hidden', 'false');
            });
            closeBtn.addEventListener('click', () => {
                chatSidebar.setAttribute('aria-hidden', 'true');
            });
        });
    </script>
@endpush
