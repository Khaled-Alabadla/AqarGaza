@extends('layouts.front')

@section('title', $page->title)

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/favorites_styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/properties_styles.css') }}">
@endpush

@section('content')
    @include('front.components.chat')
    @include('layouts.hero', [
        'title' => $page->title,
        'description' => $page->subtitle,
    ])
    <main class="main-content">
        <section class="property-listings section-padding">
            <div class="container">
                <div class="properties-info-header">
                    <h2>العقارات المفضلة لديك</h2>
                    <p>هنا تجد جميع العقارات التي أعجبتك وقمت بحفظها للمراجعة لاحقاً.</p>
                </div>
                <div class="property-grid">
                    <!-- Populated by JavaScript -->
                </div>
                <p id="no-favorites-message" style="display: none;">لا توجد عقارات في المفضلة بعد. ابدأ بإضافة بعض العقارات!
                </p>
                <div class="pagination">
                    <!-- Populated by JavaScript -->
                </div>
            </div>
        </section>
    </main>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/favorites.js') }}"></script>
@endpush
