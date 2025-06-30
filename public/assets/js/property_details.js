document.addEventListener("DOMContentLoaded", () => {
  // ** وظيفة معرض الصور في قسم "صور العقار" (property-images card) **

  // الحصول على عنصر الصورة الكبيرة التي ستعرض الصور في المعرض
  // تأكد أن الـ ID لهذه الصورة في ملف HTML هو "main-property-img"
  const mainPropertyImg = document.getElementById("main-property-img");

  // الحصول على جميع العناصر التي تمثل الصور المصغرة (thumbnails)
  // الموجودة داخل الحاوية الخاصة بالصور المصغرة (.thumbnails-container)
  const thumbnails = document.querySelectorAll(
    ".thumbnails-container .thumbnail"
  );

  // إضافة مستمع حدث 'click' لكل صورة مصغرة تم العثور عليها
  thumbnails.forEach((thumbnail) => {
    thumbnail.addEventListener("click", () => {
      // 1. إزالة فئة 'active' من جميع الصور المصغرة
      // هذا يضمن إزالة التحديد البصري (مثل الحدود الملونة) من أي صورة مصغرة كانت محددة سابقًا.
      thumbnails.forEach((t) => t.classList.remove("active"));

      // 2. إضافة فئة 'active' إلى الصورة المصغرة التي تم النقر عليها حاليًا
      // هذا يضيف التحديد البصري للصورة المصغرة الجديدة التي اختارها المستخدم.
      thumbnail.classList.add("active");

      // 3. تغيير مصدر (src) الصورة الكبيرة (mainPropertyImg)
      // يتم تغيير الصورة الكبيرة لعرض الصورة الكاملة المطابقة للصورة المصغرة المنقورة.
      // الخاصية 'data-full-src' في HTML يجب أن تحتوي على المسار الكامل لتلك الصورة.
      mainPropertyImg.src = thumbnail.dataset.fullSrc;
    });
  });

  // ** تهيئة الصورة الرئيسية لمعرض الصور عند تحميل الصفحة **
  // هذا يضمن عرض الصورة الأولى من المعرض بشكل افتراضي عند زيارة الصفحة.
  // يتم التحقق من وجود صور مصغرة وعنصر الصورة الرئيسية قبل التنفيذ لتجنب الأخطاء.
  if (thumbnails.length > 0 && mainPropertyImg) {
    // تعيين مصدر الصورة الكبيرة لتكون هي الصورة الكاملة لأول صورة مصغرة في القائمة.
    mainPropertyImg.src = thumbnails[0].dataset.fullSrc;
    // إضافة فئة 'active' إلى أول صورة مصغرة لتمييزها بصريًا عند التحميل.
    thumbnails[0].classList.add("active");
  }

  // ** وظيفة زر الإعجاب (Favorite Button) على عقارات مشابهة **
  // هذه الوظيفة تبدل أيقونة القلب (فارغ/ممتلئ) عند النقر على زر الإعجاب.
  document
    .querySelectorAll(".similar-properties .favorite-btn")
    .forEach((button) => {
      button.addEventListener("click", (event) => {
        const icon = event.currentTarget.querySelector("i");
        icon.classList.toggle("far"); // تبديل إلى أيقونة القلب الفارغ (Font Awesome Regular)
        icon.classList.toggle("fas"); // تبديل إلى أيقونة القلب الممتلئ (Font Awesome Solid)
        console.log("Favorite status toggled for a similar property.");
      });
    });

  // ** وظائف الأزرار والتفاعلات الأخرى في صفحة تفاصيل العقار **

  // وظيفة زر "المزيد" (More Details Button)
  // عند النقر عليه، يعرض تنبيهاً (alert) توضيحياً.
  const moreDetailsBtn = document.querySelector(".btn-more-details");
  if (moreDetailsBtn) {
    moreDetailsBtn.addEventListener("click", () => {
      alert("هنا يمكن عرض المزيد من التفاصيل عن العقار!");
      console.log("More details button clicked.");
    });
  }

  // وظيفة زر "تواصل مع المالك" (Contact Owner Button)
  // عند النقر عليه، يعرض تنبيهاً توضيحياً.
  const contactOwnerBtn = document.querySelector(".btn-contact-owner");
  if (contactOwnerBtn) {
    contactOwnerBtn.addEventListener("click", () => {
      alert("سيتم فتح نموذج اتصال بالمالك أو الاتصال بالرقم مباشرة.");
      console.log("Contact Owner button clicked.");
    });
  }

  // وظيفة زر "إرسال تعليق" (Send Comment Button)
  // عند النقر عليه، يتحقق مما إذا كان حقل النص فارغًا، ثم يعرض تنبيهاً بالرسالة أو يطلب إدخال نص.
  const sendCommentBtn = document.querySelector(".btn-send-comment");
  const commentTextarea = document.querySelector(".comment-section textarea");

  if (sendCommentBtn && commentTextarea) {
    sendCommentBtn.addEventListener("click", () => {
      const comment = commentTextarea.value.trim(); // الحصول على النص وإزالة المسافات البيضاء الزائدة
      if (comment) {
        alert("تم إرسال تعليقك/استفسارك بنجاح: " + comment);
        console.log("Comment submitted:", comment);
        commentTextarea.value = ""; // مسح حقل النص بعد الإرسال
      } else {
        alert("الرجاء كتابة تعليق أو استفسار قبل الإرسال.");
      }
    });
  }
});
