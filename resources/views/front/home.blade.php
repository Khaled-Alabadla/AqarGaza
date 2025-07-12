@extends('layouts.front')

@section('title', 'عقار غزة - كل ما تحتاجه عن العقارات')
<!-- .........   Sections  ............-->

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/home_styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/properties_styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/favorite_styles.css') }}">
@endpush

@section('content')
    @include('front.components.favorites')
    @include('front.components.chat')
    <section class="hero-section">
        <div class="hero-bg"></div>
        <div class="hero-overlay"></div>

        <div class="container hero-content">
            <h1 class="hero-title"> {{ $page->title }} </h1>
            <p class="hero-subtitle"> {{ $page->subtitle }} </p>

            <section class="filter-section">
                <div class="filter-container">
                    <!-- City Filter -->
                    <div class="filter-group">
                        <div class="select-box" data-filter-name="city" data-selected-value="{{ request('city', 'all') }}"
                            role="combobox" aria-expanded="false" tabindex="0">
                            <div class="selected-option" aria-label="اختر المحافظة">
                                @if (request('city') && $cities->find(request('city')))
                                    {{ $cities->find(request('city'))->name }}
                                @else
                                    المحافظة
                                @endif
                            </div>
                            <div class="options-container" role="listbox">
                                <div class="option {{ request('city') == 'all' ? 'selected' : '' }}" data-value="all"
                                    role="option" tabindex="-1">الكل</div>
                                @foreach ($cities as $city)
                                    <div class="option {{ request('city') == $city->id ? 'selected' : '' }}"
                                        data-value="{{ $city->id }}" role="option" tabindex="-1">{{ $city->name }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Category Filter -->
                    <div class="filter-group">
                        <div class="select-box" data-filter-name="propertyType"
                            data-selected-value="{{ request('propertyType', 'all') }}" role="combobox"
                            aria-expanded="false" tabindex="0">
                            <div class="selected-option" aria-label="اختر نوع العقار">
                                @if (request('propertyType') && $categories->find(request('propertyType')))
                                    {{ $categories->find(request('propertyType'))->name }}
                                @else
                                    النوع
                                @endif
                            </div>
                            <div class="options-container" role="listbox">
                                <div class="option {{ request('propertyType') == 'all' ? 'selected' : '' }}"
                                    data-value="all" role="option" tabindex="-1">الكل</div>
                                @foreach ($categories as $category)
                                    <div class="option {{ request('propertyType') == $category->id ? 'selected' : '' }}"
                                        data-value="{{ $category->id }}" role="option" tabindex="-1">
                                        {{ $category->name }}</div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Search Button -->
                    <div class="filter-group">
                        <button class="btn btn-search" id="search-btn">
                            <i class="fas fa-search"></i>
                            <span>بحث</span>
                        </button>
                    </div>
                </div>
            </section>
        </div>
    </section>

    <section class="featured-properties section-padding">
        <div class="featured__overlay"></div>
        <div class="container featured-properties__inner">
            <h2 class="section-title">اعثر على عقارك المثالي</h2>
            <div class="properties-grid">
                @foreach ($randomProperties as $property)
                    @include('front.components.card')
                @endforeach
            </div>
        </div>
    </section>

    <section class="services section-padding bg-light-gray">
        <div class="container services__inner">
            <h2 class="section-title">خدمات عقارية حديثة، لا مثيل لها</h2>
            <div class="services-grid grid-gap">
                <div class="service-item main-card interactive-card">
                    <div class="service-icon">
                        <img src="../images/mobile_13489708.png" alt="إدارة الممتلكات">
                    </div>
                    <h3>إدارة الممتلكات</h3>
                    <p>نعتني بإدارة وصيانة العقارات لتحقيق أعلى قيمة استثمارية</p>
                </div>
                <div class="service-item main-card interactive-card">
                    <div class="service-icon">
                        <img src="../images/mobile_13489708.png" alt="التأجير العقاري">
                    </div>
                    <h3>التأجير العقاري</h3>
                    <p>نوفر خدمة سهلة وموثوقة لتأجير الشقق والفيلات والمكاتب التجارية</p>
                </div>
                <div class="service-item main-card interactive-card">
                    <div class="service-icon">
                        <img src="../images/mobile_13489708.png" alt="البيع والشراء">
                    </div>
                    <h3>البيع والشراء</h3>
                    <p>نقدم خدمة شاملة لبيع وشراء العقارات بأسعار تنافسية</p>
                </div>
            </div>
        </div>
    </section>


    <section class="latest-listings section-padding">
        <div class="container">
            <div class="latest-listings__header">
                <h2 class="section-title">نقدم العقارات الأحدث لك</h2>
                <p class="section-description">عقاري يوفر لك معلومات حديثة وموثوقة تجعل العثور على العقار الذي تحلم به
                    بسهولة وسرعة</p>
            </div>

            <div class="latest-listings__grid grid-gap">
                @forelse ($latestProperties as $property)
                    @include('front.components.card')
                @empty
                    <p>لا توجد عقارات متاحة.</p>
                @endforelse
            </div>
        </div>
    </section>
    ```

    <section class="blog-section section-padding bg-light-gray">
        <div class="container">
            <div class="blog-header">
                <h2 class="section-title_blog">المدونة</h2>
                <div class="more_tag">
                    <p class="section-description">اطلع على أحدث المواضيع والمقالات في المجال العقاري</p>
                    <div class="blog-controls">
                        <a href="#" class="blog-back btn-link">
                            <i class="fas fa-arrow-left"></i>
                            تفقد صفحة المدونة
                        </a>
                    </div>
                </div>

            </div>

            <div class="blog-content grid-gap">
                <div class="blog-col blog-col--large main-card interactive-card">
                    <div class="blog-item blog-item--large">
                        <img src="{{ asset($mainBlog->image) }}" alt="مقال مميز">
                        <div class="blog-item__overlay">
                            <div class="blog-item__content">
                                <h3 class="blog-featured-title"> {{ $mainBlog->title }} </h3>
                                <a href="#" class="btn-read-more interactive-button">اقرأ المزيد</a>
                            </div>
                        </div>
                    </div>
                </div>
                @foreach ($blogs as $blog)
                    <div class="blog-col blog-col--small">
                        <div class="blog-item main-card interactive-card"> <img src="{{ asset($blog->image) }}"
                                alt="مقال صغير">
                            <div class="blog-item__content">
                                <h3 class="blog-item__title"> {{ $blog->title }} </h3>
                                <a href="#" class="btn-read-more interactive-button">اقرأ المزيد</a>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        </div>
    </section>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const selectBoxes = document.querySelectorAll('.select-box');
            const searchButton = document.querySelector('#search-btn');

            // Log if select boxes are found
            if (!selectBoxes.length) {
                return;
            }

            // Read initial query parameters
            const urlParams = new URLSearchParams(window.location.search);
            const initialFilters = {
                city: urlParams.get('city') || 'all',
                propertyType: urlParams.get('propertyType') || 'all',
            };

            // Initialize dropdowns with query parameters
            selectBoxes.forEach((selectBox) => {
                const filterName = selectBox.dataset.filterName;
                const selectedValue = initialFilters[filterName];
                selectBox.dataset.selectedValue = selectedValue;
                const selectedOption = selectBox.querySelector('.selected-option');
                const optionsContainer = selectBox.querySelector('.options-container');
                const options = optionsContainer.querySelectorAll('.option');

                if (!selectedOption || !optionsContainer) {

                    return;
                }

                options.forEach((option) => {
                    if (option.dataset.value === selectedValue) {
                        selectedOption.textContent = option.textContent;
                        option.classList.add('selected');
                    } else {
                        option.classList.remove('selected');
                    }
                });
            });

            // Handle dropdown selections
            selectBoxes.forEach((selectBox, selectIndex) => {
                const selectedOption = selectBox.querySelector('.selected-option');
                const optionsContainer = selectBox.querySelector('.options-container');
                const options = optionsContainer.querySelectorAll('.option');

                if (!selectedOption || !optionsContainer) {

                    return;
                }

                // Click handler for selected-option
                selectedOption.addEventListener('click', () => {
                    selectBoxes.forEach((otherSelectBox) => {
                        if (otherSelectBox !== selectBox) {
                            otherSelectBox.querySelector('.options-container').style
                                .display = 'none';
                            otherSelectBox.setAttribute('aria-expanded', 'false');
                        }
                    });
                    const isOpen = optionsContainer.style.display === 'block';
                    optionsContainer.style.display = isOpen ? 'none' : 'block';
                    selectBox.setAttribute('aria-expanded', !isOpen);
                });

                // Keyboard handler for select-box
                selectBox.addEventListener('keydown', (event) => {
                    if (event.key === 'Enter' || event.key === ' ') {
                        event.preventDefault();
                        const isOpen = optionsContainer.style.display === 'block';
                        optionsContainer.style.display = isOpen ? 'none' : 'block';
                        selectBox.setAttribute('aria-expanded', !isOpen);
                        if (!isOpen && options.length) {
                            options[0].focus();
                        }

                    }
                });

                // Option click handler
                options.forEach((option, index) => {
                    option.addEventListener('click', () => {
                        selectedOption.textContent = option.textContent;
                        selectBox.dataset.selectedValue = option.dataset.value;
                        options.forEach((opt) => opt.classList.remove('selected'));
                        option.classList.add('selected');
                        optionsContainer.style.display = 'none';
                        selectBox.setAttribute('aria-expanded', 'false');
                        selectBox.focus();
                    });

                    // Keyboard handler for options
                    option.addEventListener('keydown', (event) => {
                        if (event.key === 'Enter' || event.key === ' ') {
                            event.preventDefault();
                            selectedOption.textContent = option.textContent;
                            selectBox.dataset.selectedValue = option.dataset.value;
                            options.forEach((opt) => opt.classList.remove('selected'));
                            option.classList.add('selected');
                            optionsContainer.style.display = 'none';
                            selectBox.setAttribute('aria-expanded', 'false');
                            selectBox.focus();
                        } else if (event.key === 'ArrowDown') {
                            event.preventDefault();
                            const nextOption = options[index + 1] || options[0];
                            nextOption.focus();
                        } else if (event.key === 'ArrowUp') {
                            event.preventDefault();
                            const prevOption = options[index - 1] || options[options
                                .length - 1];
                            prevOption.focus();
                        } else if (event.key === 'Escape') {
                            optionsContainer.style.display = 'none';
                            selectBox.setAttribute('aria-expanded', 'false');
                            selectBox.focus();
                        }
                    });
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', (event) => {
                    if (!selectBox.contains(event.target)) {
                        optionsContainer.style.display = 'none';
                        selectBox.setAttribute('aria-expanded', 'false');
                    }
                });
            });

            // Handle search button click with debouncing
            if (searchButton) {
                let debounceTimeout;
                searchButton.addEventListener('click', () => {
                    clearTimeout(debounceTimeout);
                    debounceTimeout = setTimeout(() => {
                        const filters = {};
                        selectBoxes.forEach((selectBox) => {
                            filters[selectBox.dataset.filterName] = selectBox.dataset
                                .selectedValue || 'all';
                        });
                        const queryString = new URLSearchParams(filters).toString();
                        window.location.href = `/properties?${queryString}`;
                    }, 300);
                });
            } else {}
        });
    </script>
@endpush
