@extends('layouts.front')

@section('title', 'ابحث عن منزل أحلامك')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/properties_styles.css') }}">
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
                            <div class="option" data-value="شقة">شقة</div>
                            <div class="option" data-value="فيلا">فيلا</div>
                            <div class="option" data-value="منزل">منزل</div>
                            <div class="option" data-value="استوديو">استوديو</div>
                        </div>
                    </div>
                </div>

                <div class="filter-group">
                    <div class="select-box" data-filter-name="city">
                        <div class="selected-option">العنوان </div>
                        <div class="options-container">
                            <div class="option" data-value="all">الكل</div>
                            <div class="option" data-value="غزة">غزة</div>
                            <div class="option" data-value="خانيونس">خانيونس</div>
                            <div class="option" data-value="رفح">رفح</div>
                            <div class="option" data-value="الوسطى">الوسطى</div>
                            <div class="option" data-value="شمال غزة">شمال غزة</div>
                            <div class="option" data-value="القدس">القدس</div>
                            <div class="option" data-value="رام الله">رام الله</div>
                            <div class="option" data-value="نابلس">نابلس</div>
                            <div class="option" data-value="الخليل">الخليل</div>
                            <div class="option" data-value="بيت لحم">بيت لحم</div>
                            <div class="option" data-value="يافا">يافا</div>
                            <div class="option" data-value="حيفا">حيفا</div>
                            <div class="option" data-value="عكا">عكا</div>
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

                <button class="btn btn-search">
                    <i class="fas fa-search"></i>
                    بحث
                </button>
            </div>
        </section>
        <section class="property-listings">
            <div class="property-grid">
                <div class="property-card">
                    <div class="property-image">
                        <img src="{{ asset('assets/img/landing.jpg') }}" alt="Property Image">
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
            </div>
            <div class="pagination">
                <span class="pagination-dot active"></span>
                <span class="pagination-dot"></span>
                <span class="pagination-dot"></span>
                <span class="pagination-dot"></span>
            </div>
        </section>

    </main>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/properties.js') }}"></script>
@endpush
