@extends('layouts.front')

@section('title')
    {{ $property->title }}
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/property_details_styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/favorite_styles.css') }}">

    <style>
        .hero-bg {
            background-image: url({{ asset($property->main_image) }})
        }
    </style>
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
                </div>
            </section>

            <section class="about-property card">
                <h2>عن العقار</h2>
                <p>{{ $property->description }}</p>
            </section>

            <section class="main-hero-property-image card">
                <h2>الصورة الرئيسية</h2>
                <div class="main-hero-image-container">
                    <img src="{{ asset($property->main_image) }}" alt="صورة العقار الرئيسية">
                </div>
            </section>

            @if ($property->images->count() > 0)
                <section class="property-images card">
                    <h2>صور إضافية</h2>
                    <div class="main-image-container">
                        <img id="main-property-img"
                            src="{{ $property->images->first() ? asset($property->images->first()->image_path) : '' }}"
                            alt="Property Main Image">
                    </div>
                    <div class="thumbnails-container">

                        @foreach ($property->images as $image)
                            <img class="thumbnail {{ $loop->first ? 'active' : '' }}"
                                src="{{ asset($image->image_path) }}" alt="Thumbnail {{ $loop->iteration }}"
                                data-full-src="{{ asset($image->image_path) }}">
                        @endforeach

                    </div>
                </section>
            @endif

        </div>

        <div class="side-info-column">
            <section class="owner-info card">
                <h2>صاحب ملك العقار</h2>
                <div class="owner-details">
                    <div class="owner-avatar">
                        @if ($property->user->image)
                            <img src="{{ asset($property->user->image) }}" alt="">
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
                @if (auth()->user() != $property->user)
                    <button class="btn btn-contact-owner" data-property-id="{{ $property->id }}"
                        onclick="initiateChat({{ $property->id }})">تواصل مع المالك</button>
                @endif


                @if ($similarProperties->count())
                    <section class="similar-properties">
                        <h2>عقارات أخرى مشابهة</h2>
                        <div class="similar-grid">
                            @foreach ($similarProperties as $similarProperty)
                                <div class="property-card">
                                    <div class="property-image">
                                        <img src="{{ asset($similarProperty->main_image) }}" alt="Similar Property Image">
                                        <button class="favorite-btn" data-property-id="{{ $similarProperty->id }}"><i
                                                class="far fa-heart"></i></button>
                                    </div>
                                    <div class="property-info">
                                        <span class="property-price">{{ number_format($similarProperty->price, 2) }}
                                            {{ $similarProperty->currency }}</span>
                                        <p class="property-location">{{ $similarProperty->city->name }}،
                                            {{ $similarProperty->zone->name }}</p>
                                        <div class="property-features">
                                            <div><i class="fas fa-bed"></i>
                                                <span>{{ $similarProperty->rooms ?? 'غير محدد' }}
                                                    غرف</span>
                                            </div>
                                            <div><i class="fas fa-bath"></i>
                                                <span>{{ $similarProperty->bathrooms ?? 'غير محدد' }}
                                                    حمام</span>
                                            </div>
                                            <div><i class="fas fa-ruler-combined"></i> <span>{{ $similarProperty->area }}
                                                    م²</span>
                                            </div>
                                        </div>
                                        <p class="property-status">{{ $similarProperty->title }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .btn-contact-owner:disabled {
            background: #ccc;
            cursor: not-allowed;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/property_details.js') }}"></script>
    <script>
        function initiateChat(propertyId) {
            const button = document.querySelector(`.btn-contact-owner[data-property-id="${propertyId}"]`);
            button.disabled = true;
            button.textContent = 'جاري التحميل...';

            if (!{{ auth()->check() ? 'true' : 'false' }}) {
                alert('يرجى تسجيل الدخول للتواصل مع المالك');
                window.location.href = '{{ route('login') }}';
                button.disabled = false;
                button.textContent = 'تواصل مع المالك';
                return;
            }

            fetch('{{ url('/chats/initiate') }}/' + propertyId, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('خطأ');
                    }
                    return response.json();
                })
                .then(data => {
                    const propertyTitle = encodeURIComponent('{{ $property->title }}');
                    window.location.href = '{{ route('chat.index') }}?chat_id=' + data.chat_id
                })
                .catch(error => {
                    console.error('Error initiating chat:', error);
                    button.disabled = false;
                    button.textContent = 'تواصل مع المالك';
                });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const thumbnails = document.querySelectorAll('.thumbnail');
            const mainImage = document.getElementById('main-property-img');
            thumbnails.forEach(thumbnail => {
                thumbnail.addEventListener('click', function() {
                    thumbnails.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    mainImage.src = this.dataset.fullSrc;
                });
            });

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
