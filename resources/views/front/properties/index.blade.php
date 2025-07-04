@extends('layouts.front')

@section('title', 'ابحث عن منزل أحلامك')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/properties_styles.css') }}">
    <style>
        .property-card:not(:nth-child(3)) {
            cursor: pointer;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('assets/css/favorite_styles.css') }}">
@endpush

<!-- Add CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('content')

    @include('front.components.favorites')

    @include('front.components.chat')

    @include('layouts.hero', [
        'title' => 'جميع العقارات',
        'description' => 'يمكنك تصفح جميع العقارات وفلترتها لإيجاد العقار المناسب لك',
    ])
    <main class="main-content">
        <section class="filter-section">
            <div class="filter-container">
                <div class="filter-group">
                    <div class="select-box" data-filter-name="type">
                        <div class="selected-option">إيجار / بيع</div>
                        <div class="options-container">
                            <div class="option" data-value="all">الكل</div>
                            <div class="option" data-value="rent">إيجار</div>
                            <div class="option" data-value="sale">بيع</div>
                        </div>
                    </div>
                </div>

                <div class="filter-group">
                    <div class="select-box" data-filter-name="propertyType">
                        <div class="selected-option">النوع</div>
                        <div class="options-container">
                            <div class="option" data-value="all">الكل</div>
                            @foreach ($categories as $category)
                                <div class="option" data-value="{{ $category->id }}">{{ $category->name }}</div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="filter-group">
                    <div class="select-box" data-filter-name="city">
                        <div class="selected-option">المحافظة</div>
                        <div class="options-container">
                            <div class="option" data-value="all">الكل</div>
                            @foreach ($cities as $city)
                                <div class="option" data-value="{{ $city->id }}">{{ $city->name }}</div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="filter-group">
                    <div class="select-box" data-filter-name="zone">
                        <div class="selected-option">المنطقة</div>
                        <div class="options-container">
                            <div class="option" data-value="all">الكل</div>
                        </div>
                    </div>
                </div>

                <div class="filter-group">
                    <div class="select-box" data-filter-name="area">
                        <div class="selected-option">المساحة</div>
                        <div class="options-container">
                            <div class="option" data-value="all">الكل</div>
                            <div class="option" data-value="50-100">50-100 م²</div>
                            <div class="option" data-value="101-150">101-150 م²</div>
                            <div class="option" data-value="151-200">151-200 م²</div>
                            <div class="option" data-value="201-300">201-300 م²</div>
                            <div class="option" data-value="301+">301+ م²</div>
                        </div>
                    </div>
                </div>

                <div class="filter-group">
                    <div class="select-box" data-filter-name="currency">
                        <div class="selected-option">العملة</div>
                        <div class="options-container">
                            <div class="option" data-value="all">الكل</div>
                            <div class="option" data-value="USD">دولار ($)</div>
                            <div class="option" data-value="ILS">شيكل (₪)</div>
                            <div class="option" data-value="JOD">دينار (JD)</div>
                        </div>
                    </div>
                </div>

                <div class="filter-group">
                    <div class="select-box" data-filter-name="price">
                        <div class="selected-option">السعر</div>
                        <div class="options-container">
                            <div class="option" data-value="all">الكل</div>
                            <div class="option" data-value="0-500">0-500</div>
                            <div class="option" data-value="501-1000">501-1000</div>
                            <div class="option" data-value="1001-2000">1001-2000</div>
                            <div class="option" data-value="2001+">2001+</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="property-listings">
            <div class="property-grid">
                @foreach ($properties as $property)
                    <div class="card">
                        <div class="property-card">
                            <div class="property-image">
                                <img src="{{ asset('Uploads/' . $property->main_image) }}" alt="Property Image">
                                <button class="favorite-btn"><i class="far fa-heart"></i></button>
                            </div>
                            <div class="property-info">
                                <h2 class="property-name">{{ $property->title }}</h2>
                                <span class="property-price">{{ $property->price }}
                                    @if ($property->currency == 'USD')
                                        <span class="currency">دولار</span>
                                    @elseif ($property->currency == 'ILS')
                                        <span class="currency">شيكل</span>
                                    @elseif ($property->currency == 'JOD')
                                        <span class="currency">دينار</span>
                                    @else
                                    @endif
                                </span>
                                <p class="property-type">
                                    @if ($property->type == 'rent')
                                        <span class="badge badge-rent">إيجار</span>
                                    @elseif ($property->type == 'sale')
                                        <span class="badge badge-sale">بيع</span>
                                    @else
                                    @endif
                                </p>
                                <p class="property-location">{{ $property->city->name }}، {{ $property->zone->name }}</p>
                                <div class="property-features">
                                    @if ($property->rooms)
                                        <div><i class="fas fa-bed"></i> <span>{{ $property->rooms }} غرف</span></div>
                                    @endif
                                    @if ($property->bathrooms)
                                        <div><i class="fas fa-bath"></i> <span>{{ $property->bathrooms }} حمامات</span>
                                        </div>
                                    @endif
                                    <div><i class="fas fa-ruler-combined"></i> <span>{{ $property->area }} م²</span></div>
                                </div>
                                <a class="btn_card" href="{{ route('front.properties.show', $property->id) }}">المزيد من
                                    التفاصيل</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            {{ $properties->links() }}
        </section>
    </main>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/properties.js') }}"></script>
@endpush
