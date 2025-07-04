@extends('layouts.front')

@section('title', 'العقارات المفضلة لديك')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/favorites_styles.css') }}">
@endpush



@section('content')

    @include('front.components.chat')
    @include('layouts.hero', [
        'title' => 'عقاراتي المفضلة',
        'description' => 'استعرض العقارات التي قمت بحفظها للعثور على منزل أحلامك.',
    ])
    <main class="main-content">
        <section class="property-listings section-padding">
            <div class="container">
                <div class="properties-info-header">
                    <h2>العقارات المفضلة لديك</h2>
                    <p>هنا تجد جميع العقارات التي أعجبتك وقمت بحفظها للمراجعة لاحقاً.</p>
                </div>
                <div class="property-grid">
                </div>
                <div class="pagination">
                </div>
                <p id="no-favorites-message" class="no-favorites-message" style="display: none;">
                    لا توجد عقارات في المفضلة بعد. ابدأ بإضافة بعض العقارات!
                </p>
            </div>
        </section>
    </main>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/favorites.js') }}"></script>
@endpush
