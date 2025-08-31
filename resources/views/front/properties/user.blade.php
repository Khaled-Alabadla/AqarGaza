@extends('layouts.front')

@section('title', $page->title)

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/my_aqar_styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/all.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@section('content')
    @include('front.components.favorites')
    @include('front.components.chat')
    @include('layouts.hero', [
        'title' => $page->title,
        'description' => $page->subtitle,
    ])

    <main class="main-content">
        <section class="properties-list-section section-padding">
            <div class="container">
                <div class="my-properties-grid">
                    @foreach ($properties as $property)
                        <div class="property-card" id="my-property-{{ $property->id }}">
                            <div class="property-image">
                                <img src="{{ asset($property->main_image) }}" alt="عقار {{ $property->id }}" />
                                <div class="options-dropdown-container">
                                    <button class="options-btn" aria-label="خيارات العقار">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <div class="dropdown-content">
                                        <a href="{{ route('properties.edit', $property->id) }}" class="edit-btn"><i
                                                class="fas fa-edit"></i> تعديل</a>
                                        <a href="#" class="delete-btn" data-property-id="{{ $property->id }}"><i
                                                class="fas fa-trash-alt"></i> حذف</a>
                                    </div>
                                </div>
                            </div>
                            <div class="property-info">
                                <h3 class="property-name">{{ $property->title }}</h3>
                                <span class="property-price">{{ $property->price }}
                                    @if ($property->currency == 'USD')
                                        دولار
                                    @elseif ($property->currency == 'JOD')
                                        دينار
                                    @else
                                        شيكل
                                    @endif
                                </span>
                                <p class="property-type">{{ $property->type == 'rent' ? 'تأجير' : 'بيع' }}</p>
                                <p class="property-location">{{ $property->zone->name }}، {{ $property->city->name }} </p>
                                <div class="property-features">
                                    @if ($property->rooms)
                                        <div><i class="fas fa-bed"></i> <span>{{ $property->rooms }} غرف</span></div>
                                    @endif
                                    @if ($property->bathrooms)
                                        <div><i class="fas fa-bath"></i> <span>{{ $property->bathrooms }}
                                                {{ $property->bathrooms == 1 ? 'حمام' : 'حمامات' }}</span></div>
                                    @endif
                                    @if ($property->area)
                                        <div><i class="fas fa-ruler-combined"></i> <span>{{ $property->area }} م²</span>
                                        </div>
                                    @endif
                                </div>
                                <a class="btn btn_card" href="{{ route('front.properties.show', $property->id) }}">المزيد
                                    من التفاصيل</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </main>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/my_aqar.js') }}"></script>
@endpush
