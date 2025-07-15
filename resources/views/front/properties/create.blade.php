@extends('layouts.front')

@section('title', $page->title)

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/create_property_styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/favorite_styles.css') }}">
    <style>
        /* Existing styles */
        .custom-file-input {
            position: relative;
            display: flex;
            align-items: center;
        }

        .custom-file-input input[type="file"] {
            display: none;
        }

        .custom-file-input span {
            flex-grow: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background: #f9f9f9;
            cursor: pointer;
        }

        .upload-btn {
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .upload-btn:hover {
            background: #0056b3;
        }

        .error {
            color: red;
            font-size: 14px;
            display: block;
            margin-top: 5px;
        }

        .note {
            color: #ff9900;
            font-size: 14px;
            display: block;
            margin-top: 5px;
        }

        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .image-preview {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }

        .image-preview-item {
            position: relative;
            width: 100px;
            height: 100px;
        }

        .image-preview-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        .delete-image-btn {
            position: absolute;
            top: -10px;
            right: -10px;
            background: #bd4040;
            color: white;
            border: none;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            font-size: 16px;
            line-height: 24px;
            text-align: center;
            cursor: pointer;
        }

        .delete-image-btn:hover {
            background: #cc0000;
        }

        .general-error {
            background: #ffe6e6;
            border: 1px solid red;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .general-error ul {
            margin: 0;
            padding-left: 20px;
        }
    </style>
@endpush


@section('content')

    @include('front.components.favorites')

    @include('front.components.chat')

    @include('layouts.hero', [
        'title' => $page->title,
        'description' => $page->subtitle,
    ])

    <div class="add">
        <form action="{{ route('front.properties.store') }}" method="POST" enctype="multipart/form-data" id="property-form"
            novalidate>
            @csrf
            <div class="form-container">
                @if ($errors->any())
                    <div class="error general-error">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <section class="form-section">
                    <div class="section-header">
                        <span class="section-number">1</span>
                        <h2>بيانات العقار</h2>
                    </div>
                    <p class="section-description">املأ كل البيانات للمساعدة في إشهار عقارك لأكبر عدد من المستخدمين</p>

                    <div class="form-group">
                        <label for="property-title">وصف العقار</label>
                        <input type="text" id="property-title" name="property-title" value="{{ old('property-title') }}"
                            placeholder="ادخل وصف مختصر للعقار">

                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="city_id">المدينة</label>
                            <select id="city_id" name="city_id" required>
                                <option value="">اختر المدينة</option>
                                @foreach (\App\Models\City::all() as $city)
                                    <option value="{{ $city->id }}" @selected(old('city_id') == $city->id)>{{ $city->name }}
                                    </option>
                                @endforeach
                            </select>

                        </div>
                        <div class="form-group">
                            <label for="zone_id">المنطقة</label>
                            <select id="zone_id" name="zone_id" required disabled>
                                <option value="">اختر المنطقة</option>
                                @if (old('city_id'))
                                    @foreach (\App\Models\Zone::where('city_id', old('city_id'))->get() as $zone)
                                        <option value="{{ $zone->id }}" @selected(old('zone_id') == $zone->id)>
                                            {{ $zone->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>

                        </div>
                        <div class="form-group">
                            <label for="category_id">نوع العقار</label>
                            <select id="category_id" name="category_id" required>
                                <option value="">اختر نوع العقار</option>
                                @foreach (\App\Models\Category::all() as $category)
                                    <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}
                                    </option>
                                @endforeach
                            </select>

                        </div>
                        <div class="form-group" id="bedrooms-group"
                            style="display: {{ old('category_id') &&in_array(old('category_id'),\App\Models\Category::whereIn('name', ['منزل', 'شقة', 'شاليه', 'فيلا'])->pluck('id')->toArray())? 'block': 'none' }};">
                            <label for="rooms">عدد الغرف</label>
                            <input type="number" id="rooms" name="rooms" value="{{ old('rooms') }}"
                                placeholder="أدخل عدد الغرف" min="0">

                        </div>
                        <div class="form-group" id="bathrooms-group"
                            style="display: {{ old('category_id') &&in_array(old('category_id'),\App\Models\Category::whereIn('name', ['منزل', 'شقة', 'شاليه'])->pluck('id')->toArray())? 'block': 'none' }};">
                            <label for="bathrooms">عدد الحمامات</label>
                            <input type="number" id="bathrooms" name="bathrooms" value="{{ old('bathrooms') }}"
                                placeholder="أدخل عدد الحمامات" min="0">

                        </div>
                    </div>

                    <div class="form-group radio-group">
                        <label>نوع العملية</label>
                        <div class="radio-options">
                            <input type="radio" id="rent" name="property-type" value="rent"
                                @checked(old('property-type', 'rent') == 'rent')>
                            <label for="rent">إيجار</label>
                            <input type="radio" id="sale" name="property-type" value="sale"
                                @checked(old('property-type') == 'sale')>
                            <label for="sale">بيع</label>
                        </div>

                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="property-area">مساحة العقار</label>
                            <input type="number" id="property-area" name="property-area"
                                value="{{ old('property-area') }}" placeholder="أدخل بالمتر المربع">

                        </div>
                        <div class="form-group">
                            <label for="property-price">سعر العقار</label>
                            <input type="number" id="property-price" name="property-price"
                                value="{{ old('property-price') }}" placeholder="أدخل السعر">

                        </div>
                        <div class="form-group">
                            <label for="property-currency">العملة</label>
                            <select id="property-currency" name="property-currency">
                                <option value="USD" @selected(old('property-currency') == 'USD')>دولار</option>
                                <option value="ILS" @selected(old('property-currency', 'ILS') == 'ILS')>شيكل</option>
                                <option value="JOD" @selected(old('property-currency') == 'JOD')>دينار</option>
                            </select>

                        </div>
                    </div>
                </section>

                <section class="form-section">
                    <div class="section-header">
                        <span class="section-number">2</span>
                        <h2>بيانات إضافية</h2>
                    </div>
                    <p class="section-description">كل الكلمات التي تصف بيانات عقارك جيداً ستساعدك في إشهار عقارك لأكبر عدد
                        من المستخدمين</p>

                    <div class="form-group">
                        <label for="detailed-location">العنوان بالتفصيل</label>
                        <input type="text" id="detailed-location" name="detailed-location"
                            value="{{ old('detailed-location') }}" placeholder="ادخل العنوان التفصيلي">

                    </div>

                    <div class="form-group">
                        <label for="property-description">وصف تفاصيل العقار</label>
                        <textarea id="property-description" name="property-description" rows="5" placeholder="ادخل وصف تفصيلي للعقار">{{ old('property-description') }}</textarea>

                    </div>

                    <div class="form-group">
                        <label for="owner-phone">هاتف صاحب العقار</label>
                        <input type="text" id="owner-phone" name="owner-phone"
                            value="{{ old('owner-phone', auth()->user()->phone ?? '') }}">

                    </div>
                </section>
                <section class="form-section">
                    <div class="section-header">
                        <span class="section-number">3</span>
                        <h2>الصورة الرئيسية</h2>
                    </div>
                    <p class="section-description">اختر صورة رئيسية واحدة مميزة للعقار.</p>

                    <div class="form-group file-upload-group">
                        <label for="main-property-image">صورة رئيسية واحدة</label>
                        <div class="custom-file-input">
                            <input type="file" id="main-property-image" name="main-property-image" accept="image/*">
                            <span>اختر صورة...</span>
                            <button type="button" class="upload-btn" data-input="main-property-image">تحميل</button>
                        </div>
                        <div class="image-preview" id="main-image-preview"></div>
                        @error('main-property-image')
                            <span class="error">{{ $message }}</span>
                        @else
                            <span class="error" id="main-property-image-error" style="display: none;"></span>
                            @endif
                            @if ($errors->has('main-property-image'))
                                <span class="note">ملاحظة: يرجى إعادة تحميل الصورة الرئيسية.</span>
                            @endif
                        </div>
                    </section>
                    <section class="form-section">
                        <div class="section-header">
                            <span class="section-number">4</span>
                            <h2>صور إضافية</h2>
                        </div>
                        <p class="section-description">يمكنك إضافة ما يصل إلى 5 صور إضافية للعقار لضمان رؤية أفضل</p>

                        <div class="form-group file-upload-group">
                            <label for="property-images">صور العقار (حد أقصى 5 صور)</label>
                            <div class="custom-file-input">
                                <input type="file" id="property-images" name="property-images[]" multiple
                                    accept="image/*">
                                <span>اختر صورة أو أكثر (حد أقصى 5)</span>
                                <button type="button" class="upload-btn" data-input="property-images">تحميل</button>
                            </div>
                            <div class="image-preview" id="additional-images-preview"></div>
                            @error('property-images')
                                <span class="error">{{ $message }}</span>
                            @else
                                <span class="error" id="property-images-error" style="display: none;"></span>
                                @endif
                                @if ($errors->has('property-images') || $errors->has('property-images.*'))
                                    <span class="note">ملاحظة: يرجى إعادة تحميل الصور الإضافية.</span>
                                @endif
                            </div>
                        </section>

                        <button type="submit" class="submit-btn">إضافة عقار</button>
                    </div>
                </form>

                <div class="side-info">
                    <div class="info-card help-card">
                        <i class="fas fa-question-circle"></i>
                        <h3>تحتاج لمساعدة؟ تواصل معنا</h3>
                        <p>لا تتردد في التواصل معنا إذا كان لديك استفسار أو تحتاج لمساعدة، نحن نعمل من أجلك لخدمتك.</p>
                    </div>

                    <div class="info-card privacy-card">
                        <i class="fas fa-file-alt"></i>
                        <h3>سياسة الخصوصية</h3>
                        <p>تم إعدادها لتطبيق وموقع بائع للإمدادات العقارية يجب الالتزام بالتحكم والوصول بمرونة الاستخدام.</p>
                    </div>
                </div>
            </div>
        @endsection

        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const form = document.getElementById('property-form');
                    const citySelect = document.getElementById('city_id');
                    const zoneSelect = document.getElementById('zone_id');
                    const categorySelect = document.getElementById('category_id');
                    const bedroomsGroup = document.getElementById('bedrooms-group');
                    const bathroomsGroup = document.getElementById('bathrooms-group');
                    const mainImageInput = document.getElementById('main-property-image');
                    const propertyImagesInput = document.getElementById('property-images');
                    const mainImagePreview = document.getElementById('main-image-preview');
                    const additionalImagesPreview = document.getElementById('additional-images-preview');
                    const uploadButtons = document.querySelectorAll('.upload-btn');

                    // Categories that require rooms and bathrooms (House, Apartment, Chalet)
                    const categoriesWithRooms = [
                        @foreach (\App\Models\Category::whereIn('name', ['منزل', 'شقة', 'شاليه'])->get() as $category)
                            '{{ $category->id }}',
                        @endforeach
                    ];

                    // Trigger file input click when upload button is clicked
                    uploadButtons.forEach(button => {
                        button.addEventListener('click', function() {
                            const inputId = this.getAttribute('data-input');
                            const fileInput = document.getElementById(inputId);
                            if (fileInput) {
                                fileInput.click();
                            }
                        });
                    });

                    // Function to update FileList for an input
                    function updateFileList(input, files) {
                        const dataTransfer = new DataTransfer();
                        files.forEach(file => dataTransfer.items.add(file));
                        input.files = dataTransfer.files;
                    }

                    // Handle main image preview and deletion
                    mainImageInput.addEventListener('change', function() {
                        mainImagePreview.innerHTML = '';
                        const file = this.files[0];
                        if (file) {
                            const previewContainer = document.createElement('div');
                            previewContainer.className = 'image-preview-item';
                            const img = document.createElement('img');
                            img.src = URL.createObjectURL(file);
                            const deleteBtn = document.createElement('button');
                            deleteBtn.className = 'delete-image-btn';
                            deleteBtn.textContent = '×';
                            deleteBtn.addEventListener('click', function() {
                                mainImagePreview.innerHTML = '';
                                mainImageInput.value = '';
                                mainImageInput.parentElement.querySelector('span').textContent =
                                    'اختر صورة...';
                            });
                            previewContainer.appendChild(img);
                            previewContainer.appendChild(deleteBtn);
                            mainImagePreview.appendChild(previewContainer);
                            mainImageInput.parentElement.querySelector('span').textContent = file.name;
                        } else {
                            mainImageInput.parentElement.querySelector('span').textContent = 'اختر صورة...';
                        }
                    });

                    // Handle additional images preview and deletion
                    let additionalFiles = [];
                    propertyImagesInput.addEventListener('change', function() {
                        const newFiles = Array.from(this.files);
                        additionalFiles = additionalFiles.concat(newFiles).slice(0, 5);
                        const errorSpan = document.getElementById('property-images-error');
                        if (newFiles.length > 5 || additionalFiles.length > 5) {
                            errorSpan.textContent = 'يمكنك تحميل 5 صور كحد أقصى.';
                            errorSpan.style.display = 'block';
                            this.value = '';
                            additionalFiles = additionalFiles.slice(0, 5);
                        } else {
                            errorSpan.style.display = 'none';
                        }
                        updateFileList(this, additionalFiles);
                        additionalImagesPreview.innerHTML = '';
                        additionalFiles.forEach((file, index) => {
                            const previewContainer = document.createElement('div');
                            previewContainer.className = 'image-preview-item';
                            const img = document.createElement('img');
                            img.src = URL.createObjectURL(file);
                            const deleteBtn = document.createElement('button');
                            deleteBtn.className = 'delete-image-btn';
                            deleteBtn.textContent = '×';
                            deleteBtn.addEventListener('click', function() {
                                additionalFiles.splice(index, 1);
                                updateFileList(propertyImagesInput, additionalFiles);
                                additionalImagesPreview.innerHTML = '';
                                additionalFiles.forEach((f, i) => {
                                    const newPreviewContainer = document.createElement(
                                        'div');
                                    newPreviewContainer.className = 'image-preview-item';
                                    const newImg = document.createElement('img');
                                    newImg.src = URL.createObjectURL(f);
                                    const newDeleteBtn = document.createElement('button');
                                    newDeleteBtn.className = 'delete-image-btn';
                                    newDeleteBtn.textContent = '×';
                                    newDeleteBtn.addEventListener('click', function() {
                                        additionalFiles.splice(i, 1);
                                        updateFileList(propertyImagesInput,
                                            additionalFiles);
                                        additionalImagesPreview.removeChild(
                                            newPreviewContainer);
                                        propertyImagesInput.parentElement
                                            .querySelector('span').textContent =
                                            additionalFiles.length ?
                                            `${additionalFiles.length} صورة/صور تم اختيارها` :
                                            'اختر صورة أو أكثر (حد أقصى 5)';
                                    });
                                    newPreviewContainer.appendChild(newImg);
                                    newPreviewContainer.appendChild(newDeleteBtn);
                                    additionalImagesPreview.appendChild(
                                        newPreviewContainer);
                                });
                                propertyImagesInput.parentElement.querySelector('span')
                                    .textContent = additionalFiles.length ?
                                    `${additionalFiles.length} صورة/صور تم اختيارها` :
                                    'اختر صورة أو أكثر (حد أقصى 5)';
                            });
                            previewContainer.appendChild(img);
                            previewContainer.appendChild(deleteBtn);
                            additionalImagesPreview.appendChild(previewContainer);
                        });
                        this.parentElement.querySelector('span').textContent = additionalFiles.length ?
                            `${additionalFiles.length} صورة/صور تم اختيارها` :
                            'اختر صورة أو أكثر (حد أقصى 5)';
                    });

                    // Toggle rooms and bathrooms visibility
                    categorySelect.addEventListener('change', function() {
                        const selectedCategory = this.value;
                        const showRooms = categoriesWithRooms.includes(selectedCategory);
                        bedroomsGroup.style.display = showRooms ? 'block' : 'none';
                        bathroomsGroup.style.display = showRooms ? 'block' : 'none';
                        if (!showRooms) {
                            document.getElementById('rooms').value = '';
                            document.getElementById('bathrooms').value = '';
                            document.getElementById('rooms-error').style.display = 'none';
                            document.getElementById('bathrooms-error').style.display = 'none';
                        }
                    });

                    // Load zones based on selected city
                    citySelect.addEventListener('change', function() {
                        const cityId = this.value;
                        console.log(cityId);
                        zoneSelect.innerHTML = '<option value="">اختر المنطقة</option>';
                        zoneSelect.disabled = !cityId;

                        if (cityId) {
                            fetch(`/api/zones/${cityId}`, {
                                    headers: {
                                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    data.forEach(zone => {
                                        const option = document.createElement('option');
                                        option.value = zone.id;
                                        option.textContent = zone.name;
                                        if (zone.id == '{{ old('zone_id') }}') {
                                            option.selected = true;
                                        }
                                        zoneSelect.appendChild(option);
                                    });
                                    zoneSelect.disabled = false;
                                })
                                .catch(error => {
                                    console.error('Error fetching zones:', error);
                                    zoneSelect.disabled = true;
                                });
                        }
                    });

                    // Client-side form validation
                    form.addEventListener('submit', function(event) {
                        let isValid = true;
                        const errors = {};

                        // Reset client-side error messages
                        document.querySelectorAll('.error').forEach(span => {
                            if (!span.textContent.startsWith('{{')) {
                                span.style.display = 'none';
                                span.textContent = '';
                            }
                        });

                        // Validate property-title
                        const propertyTitle = document.getElementById('property-title').value.trim();
                        if (!propertyTitle) {
                            errors['property-title'] = 'وصف العقار مطلوب.';
                            isValid = false;
                        } else if (propertyTitle.length > 255) {
                            errors['property-title'] = 'وصف العقار يجب ألا يتجاوز 255 حرفًا.';
                            isValid = false;
                        }

                        // Validate city_id
                        const cityId = citySelect.value;
                        if (!cityId) {
                            errors['city_id'] = 'المدينة مطلوبة.';
                            isValid = false;
                        }

                        // Validate zone_id
                        const zoneId = zoneSelect.value;
                        if (!zoneId) {
                            errors['zone_id'] = 'المنطقة مطلوبة.';
                            isValid = false;
                        }

                        // Validate category_id
                        const categoryId = categorySelect.value;
                        if (!categoryId) {
                            errors['category_id'] = 'نوع العقار مطلوب.';
                            isValid = false;
                        }

                        // Validate rooms and bathrooms if category requires them
                        if (categoriesWithRooms.includes(categoryId)) {
                            const rooms = document.getElementById('rooms').value;
                            if (!rooms || rooms < 0) {
                                errors['rooms'] = 'عدد الغرف مطلوب ويجب أن يكون غير سالب.';
                                isValid = false;
                            }
                            const bathrooms = document.getElementById('bathrooms').value;
                            if (!bathrooms || bathrooms < 0) {
                                errors['bathrooms'] = 'عدد الحمامات مطلوب ويجب أن يكون غير سالب.';
                                isValid = false;
                            }
                        }

                        // Validate property-location
                        const propertyLocation = document.getElementById('property-location').value.trim();
                        if (!propertyLocation) {
                            errors['property-location'] = 'موقع العقار مطلوب.';
                            isValid = false;
                        } else if (propertyLocation.length > 255) {
                            errors['property-location'] = 'موقع العقار يجب ألا يتجاوز 255 حرفًا.';
                            isValid = false;
                        }

                        // Validate property-type
                        const propertyType = document.querySelector('input[name="property-type"]:checked');
                        if (!propertyType) {
                            errors['property-type'] = 'نوع العملية مطلوب.';
                            isValid = false;
                        }

                        // Validate property-area
                        const propertyArea = document.getElementById('property-area').value.trim();
                        if (!propertyArea) {
                            errors['property-area'] = 'مساحة العقار مطلوبة.';
                            isValid = false;
                        } else if (isNaN(propertyArea) || propertyArea < 0) {
                            errors['property-area'] = 'مساحة العقار يجب أن تكون رقمًا غير سالب.';
                            isValid = false;
                        }

                        // Validate property-price
                        const propertyPrice = document.getElementById('property-price').value.trim();
                        if (!propertyPrice) {
                            errors['property-price'] = 'سعر العقار مطلوب.';
                            isValid = false;
                        } else if (isNaN(propertyPrice) || propertyPrice < 0) {
                            errors['property-price'] = 'سعر العقار يجب أن يكون رقمًا غير سالب.';
                            isValid = false;
                        }

                        // Validate property-currency
                        const propertyCurrency = document.getElementById('property-currency').value;
                        if (!['USD', 'ILS', 'JOD'].includes(propertyCurrency)) {
                            errors['property-currency'] = 'العملة مطلوبة ويجب أن تكون USD، ILS، أو JOD.';
                            isValid = false;
                        }

                        // Validate detailed-location
                        const detailedLocation = document.getElementById('detailed-location').value.trim();
                        if (!detailedLocation) {
                            errors['detailed-location'] = 'العنوان التفصيلي مطلوب.';
                            isValid = false;
                        } else if (detailedLocation.length > 255) {
                            errors['detailed-location'] = 'العنوان التفصيلي يجب ألا يتجاوز 255 حرفًا.';
                            isValid = false;
                        }

                        // Validate property-description
                        const propertyDescription = document.getElementById('property-description').value.trim();
                        if (!propertyDescription) {
                            errors['property-description'] = 'وصف تفاصيل العقار مطلوب.';
                            isValid = false;
                        }

                        // Validate owner-phone
                        const ownerPhone = document.getElementById('owner-phone').value.trim();
                        if (!ownerPhone) {
                            errors['owner-phone'] = 'هاتف صاحب العقار مطلوب.';
                            isValid = false;
                        } else if (!/^\+?\d{7,15}$/.test(ownerPhone)) {
                            errors['owner-phone'] = 'رقم الهاتف يجب أن يكون بين 7 و15 رقمًا، ويمكن أن يبدأ بـ +.';
                            isValid = false;
                        }

                        // Validate main-property-image
                        const mainImage = mainImageInput.files[0];
                        if (!mainImage) {
                            errors['main-property-image'] = 'الصورة الرئيسية مطلوبة.';
                            isValid = false;
                        } else if (!['image/jpeg', 'image/png', 'image/jpg'].includes(mainImage.type)) {
                            errors['main-property-image'] = 'الصورة الرئيسية يجب أن تكون من نوع jpeg، png، أو jpg.';
                            isValid = false;
                        } else if (mainImage.size > 4 * 1024 * 1024) {
                            errors['main-property-image'] = 'الصورة الرئيسية يجب ألا تتجاوز 4 ميغابايت.';
                            isValid = false;
                        }

                        // Validate additional images
                        const additionalImages = propertyImagesInput.files;
                        if (additionalImages.length > 5) {
                            errors['property-images'] = 'يمكنك تحميل 5 صور إضافية كحد أقصى.';
                            isValid = false;
                        } else {
                            for (let i = 0; i < additionalImages.length; i++) {
                                if (!['image/jpeg', 'image/png', 'image/jpg'].includes(additionalImages[i].type)) {
                                    errors['property-images'] =
                                        'الصور الإضافية يجب أن تكون من نوع jpeg، png، أو jpg.';
                                    isValid = false;
                                } else if (additionalImages[i].size > 4 * 1024 * 1024) {
                                    errors['property-images'] = 'إحدى الصور الإضافية تتجاوز 4 ميغابايت.';
                                    isValid = false;
                                }
                            }
                        }

                        // Display errors
                        Object.keys(errors).forEach(field => {
                            const errorSpan = document.getElementById(`${field}-error`);
                            if (errorSpan) {
                                errorSpan.textContent = errors[field];
                                errorSpan.style.display = 'block';
                            }
                        });

                        if (!isValid) {
                            event.preventDefault();
                            console.error('Client-side validation failed:', errors);
                        }
                    });
                });
            </script>
        @endpush
