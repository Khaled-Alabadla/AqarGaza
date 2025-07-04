document.addEventListener("DOMContentLoaded", () => {
  // **    'active' لروابط التنقل الرئيسية (main-nav) **

  const navLinks = document.querySelectorAll(".main-nav .nav-link");

  navLinks.forEach((link) => {
    link.addEventListener("click", (event) => {
      // منع السلوك الافتراضي للرابط (الانتقال إلى صفحة أخرى) مؤقتًا
      // يمكنك إزالة هذا السطر إذا كنت تريد أن ينقلك الرابط لصفحة فعلية مع تغيير الـ active
      event.preventDefault();

      // 1. البحث عن الرابط النشط حاليًا في القائمة الرئيسية وإزالة فئة 'active' منه
      const currentActiveNavLink = document.querySelector(
        ".main-nav .nav-link.active"
      );
      if (currentActiveNavLink) {
        currentActiveNavLink.classList.remove("active");
      }

      // 2. إضافة فئة 'active' إلى الرابط الذي تم النقر عليه في القائمة الرئيسية
      event.currentTarget.classList.add("active");
    });
  });

  // ** وظيفة تبديل الفئة 'active' لروابط القائمة الجانبية (sidebar-menu) **

  const sidebarLinks = document.querySelectorAll(".sidebar-menu .sidebar-link");

  sidebarLinks.forEach((link) => {
    link.addEventListener("click", (event) => {
      // منع السلوك الافتراضي للرابط (الانتقال إلى صفحة أخرى) مؤقتًا
      // يمكنك إزالة هذا السطر إذا كنت تريد أن ينقلك الرابط لصفحة فعلية مع تغيير الـ active
      event.preventDefault();

      // 1. البحث عن الرابط النشط حاليًا في القائمة الجانبية وإزالة فئة 'active' منه
      const currentActiveSidebarLink = document.querySelector(
        ".sidebar-menu .sidebar-link.active"
      );
      if (currentActiveSidebarLink) {
        currentActiveSidebarLink.classList.remove("active");
      }

      // 2. إضافة فئة 'active' إلى الرابط الذي تم النقر عليه في القائمة الجانبية
      event.currentTarget.classList.add("active");
    });
  });

  // ** وظيفة تبديل القائمة الجانبية (Sidebar) **

  const toggle = document.querySelector(".sidebar-toggle");
  const sidebar_header = document.querySelector(".sidebar-header");

  toggle.addEventListener("click", (e) => {
    e.preventDefault();
    sidebar_header.classList.toggle("open");
    sidebar_header.setAttribute(
      "aria-hidden",
      sidebar_header.classList.contains("open") ? "false" : "true"
    );

    // بدّل الأيقونة
    const icon = toggle.querySelector("i");
    if (sidebar_header.classList.contains("open")) {
      icon.classList.replace("fa-bars", "fa-xmark");
    } else {
      icon.classList.replace("fa-xmark", "fa-bars");
    }
  });

  // ** تحديث السنة في الفوتر تلقائياً **

  const currentYearSpan = document.getElementById("current-year");
  if (currentYearSpan) {
    // تحقق لتجنب الأخطاء إذا لم يكن العنصر موجودًا
    currentYearSpan.textContent = new Date().getFullYear();
  }
});
