@extends('layouts.front')

@section('title', 'إضافة عقار جديد')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/create_property_styles.css') }}">
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
        'title' => 'قم بإضافة عقار جديد',
        'description' => 'يمكنك إضافة أي عقار مع كافة التفاصيل اللازمة',
    ])
    <div class="add">
        <div class="form-container">
            <section class="form-section">
                <div class="section-header">
                    <span class="section-number">1</span>
                    <h2>بيانات العقار</h2>
                </div>
                <p class="section-description">املأ كل البيانات للمساعدة في إشهار عقارك لأكبر عدد من المستخدمين</p>

                <div class="form-group">
                    <label for="property-location">موقع العقار</label>
                    <input type="text" id="property-location" placeholder="ادخل موقع العقار بالكامل">
                </div>

                <div class="form-group radio-group">
                    <label>نوع العقار</label>
                    <div class="radio-options">
                        <input type="radio" id="rent" name="property-type" value="rent" checked>
                        <label for="rent">إيجار</label>
                        <input type="radio" id="sale" name="property-type" value="sale">
                        <label for="sale">بيع</label>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="property-area">مساحة العقار</label>
                        <input type="text" id="property-area" placeholder="أدخل بالمتر المربع">
                    </div>
                    <div class="form-group">
                        <label for="property-price">سعر العقار</label>
                        <input type="text" id="property-price" placeholder="أدخل السعر">
                    </div>
                    <div class="form-group">
                        <label for="property-currency">العملة</label>
                        <select id="property-currency">
                            <option value="USD">دولار</option>
                            <option value="ILS">شيكل</option>
                            <option value="JOD">دينار</option>
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
                    <label for="detailed-address">العنوان بالتفصيل</label>
                    <input type="text" id="detailed-address"
                        placeholder="الفلسطيني - غزة - الشيخ رضوان - شارع الشافعي - فيلا بجانب سوق سيارات القدس">
                </div>

                <div class="form-group">
                    <label for="property-description">وصف تفاصيل العقار</label>
                    <textarea id="property-description" rows="5"
                        placeholder="شقة ، مفروشة ، للإيجار ، بالجيش ، بالقرب من سوق السيارات ، منطقة حيوية ، يوجد بها ، مصعد ، غرفتين نوم ، صالون ، مطبخ ، حمامين ، يوجد بها عداد مياه ، عداد كهرباء ، غاز ، انترنت ، تلفون ، مولد كهرباء ، تدفئة مركزية ، إطلالة جميلة على الطبيعة ، إطلالة جميلة على الشارع"></textarea>
                </div>

                <div class="form-group">
                    <label for="owner-phone">هاتف صاحب العقار</label>
                    <input type="text" id="owner-phone" value="+970-599441544" readonly>
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
                        <input type="file" id="main-property-image" accept="image/*">
                        <span>اختر صورة...</span>
                        <button type="button" class="upload-btn">تحميل</button>
                    </div>
                </div>
            </section>
            <section class="form-section">
                <div class="section-header">
                    <span class="section-number">4</span>
                    <h2>صور إضافية</h2>
                </div>
                <p class="section-description">يمكنك إضافة صور للعقار هنا لضمان نمو للمستخدمين لأفضل رؤية وأشهر لعقارات
                </p>

                <div class="form-group file-upload-group">
                    <label for="property-images">صور العقار</label>
                    <div class="custom-file-input">
                        <input type="file" id="property-images" multiple accept="image/*">
                        <span>اختر صورة أو أكثر</span>
                        <button type="button" class="upload-btn">تحميل</button>
                    </div>
                </div>
            </section>

            <button type="submit" class="submit-btn">إضافة عقار</button>
        </div>

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
    <script src="{{ asset('assets/js/create_property.js') }}"></script>i
@endpush
</body>

</html>
