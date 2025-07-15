@extends('layouts.front')

@section('title', 'عقاراتي')

@push('styles')
@endpush

@section('content')

    <main class="main-content">

        <section class="properties-list-section section-padding">
            <div class="container">
                <div class="properties-info-header">
                    <h2>إدارة عقاراتك</h2>
                    <p>هنا تجد جميع العقارات التي أضفتها إلى موقعك.</p>
                </div>
                <div class="my-properties-grid">
                    <div class="property-card" id="my-property-1">
                        <div class="property-image">
                            <img src="../images/landing.jpg" alt="عقار 1">

                            <div class="options-dropdown-container">
                                <button class="options-btn" aria-label="خيارات العقار">
                                    <i class="fas fa-ellipsis-v"></i> </button>
                                <div class="dropdown-content">
                                    <a href="edit-property.html?id=my-property-1" class="edit-btn"><i
                                            class="fas fa-edit"></i> تعديل</a>
                                    <a href="#" class="delete-btn" data-property-id="my-property-1"><i
                                            class="fas fa-trash-alt"></i> حذف</a>
                                </div>
                            </div>
                        </div>
                        <div class="property-info">
                            <h3 class="property-name">شقة فاخرة للإيجار</h3>
                            <span class="property-price">1,250.00$</span>
                            <p class="property-type">إيجار</p>
                            <p class="property-location">غزة، فلسطين</p>
                            <div class="property-features">
                                <div><i class="fas fa-bed"></i> <span>3 غرف</span></div>
                                <div><i class="fas fa-bath"></i> <span>2 حمام</span></div>
                                <div><i class="fas fa-ruler-combined"></i> <span>150 م²</span></div>
                            </div>
                            <a class="btn btn_card" href="property-details.html?id=my-property-1">المزيد من
                                التفاصيل</a>
                        </div>
                    </div>

                    <div class="property-card" id="my-property-2">
                        <div class="property-image">
                            <img src="../images/landing.jpg" alt="عقار 2">

                            <div class="options-dropdown-container">
                                <button class="options-btn" aria-label="خيارات العقار">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-content">
                                    <a href="edit-property.html?id=my-property-2" class="edit-btn"><i
                                            class="fas fa-edit"></i> تعديل</a>
                                    <a href="#" class="delete-btn" data-property-id="my-property-2"><i
                                            class="fas fa-trash-alt"></i> حذف</a>
                                </div>
                            </div>
                        </div>
                        <div class="property-info">
                            <h3 class="property-name">فيلا عائلية للبيع</h3>
                            <span class="property-price">205,000.00$</span>
                            <p class="property-type">بيع</p>
                            <p class="property-location">الخليل، فلسطين</p>
                            <div class="property-features">
                                <div><i class="fas fa-bed"></i> <span>5 غرف</span></div>
                                <div><i class="fas fa-bath"></i> <span>4 حمام</span></div>
                                <div><i class="fas fa-ruler-combined"></i> <span>300 م²</span></div>
                            </div>
                            <a class="btn btn_card" href="property-details.html?id=my-property-2">المزيد من
                                التفاصيل</a>
                        </div>
                    </div>

                    <div class="property-card" id="my-property-3">
                        <div class="property-image">
                            <img src="../images/landing.jpg" alt="عقار 3">

                            <div class="options-dropdown-container">
                                <button class="options-btn" aria-label="خيارات العقار">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-content">
                                    <a href="edit-property.html?id=my-property-3" class="edit-btn"><i
                                            class="fas fa-edit"></i> تعديل</a>
                                    <a href="#" class="delete-btn" data-property-id="my-property-3"><i
                                            class="fas fa-trash-alt"></i> حذف</a>
                                </div>
                            </div>
                        </div>
                        <div class="property-info">
                            <h3 class="property-name">شقة استوديو مفروشة</h3>
                            <span class="property-price">500.00$</span>
                            <p class="property-type">إيجار</p>
                            <p class="property-location">رام الله، فلسطين</p>
                            <div class="property-features">
                                <div><i class="fas fa-bed"></i> <span>1 غرفة</span></div>
                                <div><i class="fas fa-bath"></i> <span>1 حمام</span></div>
                                <div><i class="fas fa-ruler-combined"></i> <span>50 م²</span></div>
                            </div>
                            <a class="btn btn_card" href="property-details.html?id=my-property-3">المزيد من
                                التفاصيل</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/my_aqar.js') }}"></script>
@endpush
