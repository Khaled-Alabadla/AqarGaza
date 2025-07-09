@extends('layouts.front')

@section('content')
    <!-- قسم (Hero) -->
    <section class="hero">
        <img src="{{ asset('assets/img/h.jpg') }}" alt="خلفية العقارات" class="hero__img">
        <div class="hero__overlay"></div>
        <div class="hero__content container">
            <div class="hero__text">
                <h1>ابحث عن<br>منزل<br>أحلامك</h1>
            </div>
            <div class="hero__search">
                <div class="tabs">
                    <a type="button" class="tab active" data-tab="sell">للبيع</a>
                    <a type="button" class="tab" data-tab="rent">للإيجار</a>
                </div>
                <form class="search-form">
                    <input type="text" name="location" placeholder="فلسطين، قطاع غزة، خان يونس">
                    <select name="property-type">
                        <option disabled selected>حدد نوع العقار</option>
                    </select>
                    <select name="rooms">
                        <option disabled selected>اختر الغرف</option>
                    </select>
                    <button type="submit" class="btn btn-search">
                        ابحث <span class="icon-search"><i class="fa-solid fa-magnifying-glass"></i></span>
                    </button>
                </form>
            </div>
        </div>
        <!-- إحصائيات عائمة في أسفل الهيرو -->
        <div class="hero__stats container">
            <div class="stat stat--customers">
                <span class="stat__text">+72 ألف مستخدم سعيد !!</span>
            </div>
            <div class="stat stat--listings">
                <div class="icon-listings"></div>
                <span class="stat__text">+200 منزل جديد كل يوم !!</span>
            </div>
        </div>
    </section>

    <!-- قسم العقارات المميزة -->
    <section class="featured-properties">
        <div class="featured__overlay"></div>
        <div class="container featured-properties__inner">
            <h2>اعثر على عقارك المثالي</h2>
            <div class="properties-grid">
                <!-- بطاقة عقار 1 -->
                <div class="property-card">
                    <div class="property-card__image">
                        <img src="{{ asset('assets/img/h.jpg') }}" alt="عقار 1">
                        <button class="favorite-btn" aria-label="إضافة للمفضلة"><i class="fa-solid fa-heart"></i></button>
                    </div>
                    <div class="property-card__tags">
                        <span class="tag">مسكن عائلي</span>
                        <span class="tag">مسبح</span>
                    </div>
                    <div class="property-card__details">
                        <div class="price">$ 96.000</div>
                        <div class="rating">
                            <span class="star filled">★</span>
                            <span class="star filled">★</span>
                            <span class="star filled">★</span>
                            <span class="star filled">★</span>
                            <span class="star filled">★</span>
                        </div>
                    </div>
                </div>
                <!-- بطاقة عقار 2 -->
                <div class="property-card">
                    <div class="property-card__image">
                        <img src="{{ asset('assets/img/h.jpg') }}" alt="عقار 2">
                        <button class="favorite-btn" aria-label="إضافة للمفضلة"><i class="fa-solid fa-heart"></i></button>
                    </div>
                    <div class="property-card__tags">
                        <span class="tag">فندق</span>
                        <span class="tag">شاطئ</span>
                    </div>
                    <div class="property-card__details">
                        <div class="price">$ 205.000</div>
                        <div class="rating">
                            <span class="star filled">★</span>
                            <span class="star filled">★</span>
                            <span class="star filled">★</span>
                            <span class="star">★</span>
                            <span class="star">★</span>
                        </div>
                    </div>
                </div>
                <!-- بطاقة عقار 3 -->
                <div class="property-card">
                    <div class="property-card__image">
                        <img src="{{ asset('assets/img/h.jpg') }}" alt="عقار 3">
                        <button class="favorite-btn" aria-label="إضافة للمفضلة"><i class="fa-solid fa-heart"></i></button>
                    </div>
                    <div class="property-card__tags">
                        <span class="tag">منزل فردي</span>
                        <span class="tag">مسبح</span>
                    </div>
                    <div class="property-card__details">
                        <div class="price">$ 45.000</div>
                        <div class="rating">
                            <span class="star filled">★</span>
                            <span class="star filled">★</span>
                            <span class="star">★</span>
                            <span class="star">★</span>
                            <span class="star">★</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- قسم الخدمات العقارية -->
    <section class="services">
        <div class="container services__inner">
            <h2>خدمات عقارية حديثة، لا مثيل لها</h2>
            <div class="services-grid">
                <div class="service-item">
                    <div class="service-icon">
                        <img src="{{ asset('assets/img/aqar.png') }}" alt="إدارة الممتلكات">
                    </div>
                    <h3>إدارة الممتلكات</h3>
                    <p>نعتني بإدارة وصيانة العقارات لتحقيق أعلى قيمة استثمارية</p>
                </div>
                <div class="service-item">
                    <div class="service-icon">
                        <img src="{{ asset('assets/img/aqar.png') }}" alt="التأجير العقاري">
                    </div>
                    <h3>التأجير العقاري</h3>
                    <p>نوفر خدمة سهلة وموثوقة لتأجير الشقق والفيلات والمكاتب التجارية</p>
                </div>
                <div class="service-item">
                    <div class="service-icon">
                        <img src="{{ asset('assets/img/aqar.png') }}" alt="البيع والشراء">
                    </div>
                    <h3>البيع والشراء</h3>
                    <p>نقدم خدمة شاملة لبيع وشراء العقارات بأسعار تنافسية</p>
                </div>
            </div>
        </div>
    </section>


    <!-- ===== قسم العقارات الأحدث ===== -->
    <section class="latest-listings">
        <div class="container">
            <div class="latest-listings__header">
                <h2>نقدم العقارات الأحدث لك</h2>
                <div class="latest-listings__filter">
                    <button class="filter-btn">الكل</button>
                    <button class="filter-btn active">بيع</button>
                    <button class="filter-btn">تأجير</button>
                </div>
                <p>عقاري يوفر لك معلومات حديثة وموثوقة تجعل العثور على العقار الذي تحلم به بسهولة وسرعة</p>
            </div>

            <div class="latest-listings__grid">
                <!-- بطاقة 1 -->
                <div class="listing-card">
                    <div class="listing-card__media">
                        <img src="{{ asset('assets/img/h.jpg') }}" alt="عقار جديد">
                        <button class="listing-card__fav"><i class="fa-solid fa-heart"></i></button>
                        <span class="listing-card__badge listing-card__badge--time">قبل 8 ساعات</span>
                        <span class="listing-card__badge listing-card__badge--type">شراء</span>
                    </div>
                    <div class="listing-card__details">
                        <div class="listing-card__price">1,250.00 ريال</div>
                        <div class="listing-card__meta">4 غرف 🏠 3 حمامات 🛁 500 م²📏</div>
                        <div class="listing-card__address">شارع العليا، الملك فهد، الرياض 12271</div>
                    </div>
                </div>
                <!-- بطاقة 2 -->
                <div class="listing-card">
                    <div class="listing-card__media">
                        <img src="{{ asset('assets/img/h.jpg') }}" alt="عقار جديد">
                        <button class="listing-card__fav"><i class="fa-solid fa-heart"></i></button>
                        <span class="listing-card__badge listing-card__badge--time">قبل 10 ساعات</span>
                        <span class="listing-card__badge listing-card__badge--type">بيع</span>
                    </div>
                    <div class="listing-card__details">
                        <div class="listing-card__price">980.00 ريال</div>
                        <div class="listing-card__meta">4 غرف 🏠 3 حمامات 🛁 500 م²📏</div>
                        <div class="listing-card__address">حي المروج، جدة 23456</div>
                    </div>
                </div>
                <!-- بطاقة 3 -->
                <div class="listing-card">
                    <div class="listing-card__media">
                        <img src="{{ asset('assets/img/h.jpg') }}" alt="عقار جديد">
                        <button class="listing-card__fav"><i class="fa-solid fa-heart"></i></button>
                        <span class="listing-card__badge listing-card__badge--time">قبل 6 ساعات</span>
                        <span class="listing-card__badge listing-card__badge--type">إيجار</span>
                    </div>
                    <div class="listing-card__details">
                        <div class="listing-card__price">2,200.00 ريال</div>
                        <div class="listing-card__meta">4 غرف 🏠 3 حمامات 🛁 500 م²📏</div>
                        <div class="listing-card__address">حي الورود، الرياض 11511</div>
                    </div>
                </div>
                <!-- بطاقة 4 -->
                <div class="listing-card">
                    <div class="listing-card__media">
                        <img src="{{ asset('assets/img/h.jpg') }}" alt="عقار جديد">
                        <button class="listing-card__fav"><i class="fa-solid fa-heart"></i></button>
                        <span class="listing-card__badge listing-card__badge--time">قبل 2 ساعات</span>
                        <span class="listing-card__badge listing-card__badge--type">شراء</span>
                    </div>
                    <div class="listing-card__details">
                        <div class="listing-card__price">1,100.00 ريال</div>
                        <div class="listing-card__meta">4 غرف 🏠 3 حمامات 🛁 500 م²📏</div>
                        <div class="listing-card__address">حي العليا، الرياض 12222</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== قسم المدونة  ===== -->
    <section class="blog-section">
        <div class="container">

            <div class="blog-header">
                <h2>المدونة</h2>
                <a href="#" class="blog-back"> تفقد صفحة المدونة ←</a>
                <p>اطلع على أحدث المواضيع و المقالات في المجال العقاري</p>
            </div>
            <div class="blog-content">
                <div class="blog-col blog-col--large">
                    <div class="blog-tags">
                        <span class="tag">منزل فردي</span>
                        <span class="tag">إيجار العقارات</span>
                        <span class="tag">استثمار</span>
                        <span class="tag">البيع والشراء</span>
                        <span class="tag">مسبح</span>
                    </div>
                    <div class="blog-item blog-item--large">
                        <img src="{{ asset('assets/img/h.jpg') }}" alt="مقال مميز">
                    </div>
                    <p class="blog-featured-text">
                        اكتشف الفرص الاستثمارية الواعدة في سوق العقارات للعام المقبل.
                    </p>

                </div>
                <div class="blog-col blog-col--small">
                    <div class="blog-item">
                        <img src="{{ asset('assets/img/h.jpg') }}" alt="مقال صغير">
                        <p>تعرف على كيفية العثور على منزل يناسب ميزانيتك واحتياجاتك.</p>
                    </div>
                    <div class="blog-item">
                        <img src="{{ asset('assets/img/h.jpg') }}" alt="مقال صغير">
                        <p>احصل على نصائح حول تأجير العقارات بشكل مريح وإدارتها بكفاءة.</p>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/home_styles.css') }}">
@endpush


