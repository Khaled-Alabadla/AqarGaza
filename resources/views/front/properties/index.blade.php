@extends('layouts.front')

@section('title', 'ابحث عن منزل أحلامك')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/properties_styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/favorite_styles.css') }}">
    <style>
        .property-card:not(:nth-child(3)) {
            cursor: pointer;
        }

        .select-box .selected-option {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            cursor: pointer;
        }

        .select-box .options-container {
            display: none;
            position: absolute;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 4px;
            z-index: 10;
        }

        .select-box .options-container.active {
            display: block;
        }

        .select-box .option {
            padding: 10px;
            cursor: pointer;
        }

        .select-box .option:hover,
        .select-box .option.selected {
            background-color: #f0f0f0;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
    </style>
@endpush

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('front.components.favorites')
    @include('front.components.chat')

    @include('layouts.hero', [
        'title' => $page->title,
        'description' => $page->subtitle,
    ])

    <main class="main-content">
        <section class="filter-section">
            <div class="filter-container">
                <div class="filter-group">
                    <div class="select-box" data-filter-name="type" data-selected-value="{{ request('type', 'all') }}">
                        <div class="selected-option">
                            {{ request('type') == 'rent' ? 'إيجار' : (request('type') == 'sale' ? 'بيع' : 'إيجار / بيع') }}
                        </div>
                        <div class="options-container">
                            <div class="option {{ request('type') == 'all' ? 'selected' : '' }}" data-value="all">الكل</div>
                            <div class="option {{ request('type') == 'rent' ? 'selected' : '' }}" data-value="rent">إيجار
                            </div>
                            <div class="option {{ request('type') == 'sale' ? 'selected' : '' }}" data-value="sale">بيع
                            </div>
                        </div>
                    </div>
                </div>

                <div class="filter-group">
                    <div class="select-box" data-filter-name="propertyType"
                        data-selected-value="{{ request('propertyType', 'all') }}">
                        <div class="selected-option">
                            @if (request('propertyType') && $categories->find(request('propertyType')))
                                {{ $categories->find(request('propertyType'))->name }}
                            @else
                                النوع
                            @endif
                        </div>
                        <div class="options-container">
                            <div class="option {{ request('propertyType') == 'all' ? 'selected' : '' }}" data-value="all">
                                الكل</div>
                            @foreach ($categories as $category)
                                <div class="option {{ request('propertyType') == $category->id ? 'selected' : '' }}"
                                    data-value="{{ $category->id }}">{{ $category->name }}</div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="filter-group">
                    <div class="select-box" data-filter-name="city" data-selected-value="{{ request('city', 'all') }}">
                        <div class="selected-option">
                            @if (request('city') && $cities->find(request('city')))
                                {{ $cities->find(request('city'))->name }}
                            @else
                                المحافظة
                            @endif
                        </div>
                        <div class="options-container">
                            <div class="option {{ request('city') == 'all' ? 'selected' : '' }}" data-value="all">الكل
                            </div>
                            @foreach ($cities as $city)
                                <div class="option {{ request('city') == $city->id ? 'selected' : '' }}"
                                    data-value="{{ $city->id }}">{{ $city->name }}</div>
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
                    <div class="select-box" data-filter-name="area" data-selected-value="{{ request('area', 'all') }}">
                        <div class="selected-option">
                            {{ request('area') ? str_replace('-', ' - ', request('area')) . (request('area') == '301+' ? ' م²' : ' م²') : 'المساحة' }}
                        </div>
                        <div class="options-container">
                            <div class="option {{ request('area') == 'all' ? 'selected' : '' }}" data-value="all">الكل
                            </div>
                            <div class="option {{ request('area') == '50-100' ? 'selected' : '' }}" data-value="50-100">
                                50-100 م²</div>
                            <div class="option {{ request('area') == '101-150' ? 'selected' : '' }}" data-value="101-150">
                                101-150 م²</div>
                            <div class="option {{ request('area') == '151-200' ? 'selected' : '' }}" data-value="151-200">
                                151-200 م²</div>
                            <div class="option {{ request('area') == '201-300' ? 'selected' : '' }}" data-value="201-300">
                                201-300 م²</div>
                            <div class="option {{ request('area') == '301+' ? 'selected' : '' }}" data-value="301+">301+ م²
                            </div>
                        </div>
                    </div>
                </div>

                <div class="filter-group">
                    <div class="select-box" data-filter-name="currency"
                        data-selected-value="{{ request('currency', 'all') }}">
                        <div class="selected-option">
                            {{ request('currency') == 'USD' ? 'دولار ($)' : (request('currency') == 'ILS' ? 'شيكل (₪)' : (request('currency') == 'JOD' ? 'دينار (JD)' : 'العملة')) }}
                        </div>
                        <div class="options-container">
                            <div class="option {{ request('currency') == 'all' ? 'selected' : '' }}" data-value="all">الكل
                            </div>
                            <div class="option {{ request('currency') == 'USD' ? 'selected' : '' }}" data-value="USD">دولار
                                ($)</div>
                            <div class="option {{ request('currency') == 'ILS' ? 'selected' : '' }}" data-value="ILS">شيكل
                                (₪)</div>
                            <div class="option {{ request('currency') == 'JOD' ? 'selected' : '' }}" data-value="JOD">دينار
                                (JD)</div>
                        </div>
                    </div>
                </div>

                <div class="filter-group">
                    <div class="select-box" data-filter-name="price" data-selected-value="{{ request('price', 'all') }}">
                        <div class="selected-option">
                            {{ request('price') ? str_replace('-', ' - ', request('price')) . (request('price') == '2001+' ? '+' : '') : 'السعر' }}
                        </div>
                        <div class="options-container">
                            <div class="option {{ request('price') == 'all' ? 'selected' : '' }}" data-value="all">الكل
                            </div>
                            <div class="option {{ request('price') == '0-500' ? 'selected' : '' }}" data-value="0-500">
                                0-500</div>
                            <div class="option {{ request('price') == '501-1000' ? 'selected' : '' }}"
                                data-value="501-1000">501-1000</div>
                            <div class="option {{ request('price') == '1001-2000' ? 'selected' : '' }}"
                                data-value="1001-2000">1001-2000</div>
                            <div class="option {{ request('price') == '2001+' ? 'selected' : '' }}" data-value="2001+">
                                2001+</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="property-listings">
            <div class="property-grid"></div>
            <div class="pagination"></div>
        </section>
    </main>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/properties.js') }}"></script>
@endpush
