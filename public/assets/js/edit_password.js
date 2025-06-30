document.addEventListener("DOMContentLoaded", () => {
  // Password Visibility Toggle
  document.querySelectorAll(".toggle-password").forEach((icon) => {
    icon.addEventListener("click", (event) => {
      const targetId = event.target.dataset.target;
      const passwordInput = document.getElementById(targetId);

      if (passwordInput.type === "password") {
        passwordInput.type = "text";
        event.target.classList.replace("far", "fas"); // Change to solid eye icon
        event.target.classList.replace("fa-eye", "fa-eye-slash");
      } else {
        passwordInput.type = "password";
        event.target.classList.replace("fas", "far"); // Change to outline eye icon
        event.target.classList.replace("fa-eye-slash", "fa-eye");
      }
    });
  });

  // Form Submission Handler
  const changePasswordForm = document.getElementById("changePasswordForm");
  changePasswordForm.addEventListener("submit", (e) => {
    e.preventDefault(); // Prevent default form submission

    const newPassword = document.getElementById("new-password").value;
    const confirmPassword = document.getElementById("confirm-password").value;

    if (newPassword === "") {
      alert("الرجاء إدخال كلمة المرور الجديدة.");
      return;
    }

    if (confirmPassword === "") {
      alert("الرجاء تأكيد كلمة المرور الجديدة.");
      return;
    }

    if (newPassword !== confirmPassword) {
      alert(
        "كلمة المرور الجديدة وتأكيد كلمة المرور غير متطابقين. الرجاء المحاولة مرة أخرى."
      );
      return;
    }

    // Basic password strength check (for demonstration)
    if (newPassword.length < 8) {
      alert("يجب أن تكون كلمة المرور 8 أحرف على الأقل.");
      return;
    }

    // If all checks pass
    alert("تم تغيير كلمة المرور بنجاح!");
    console.log("Password changed successfully.");

    // Optionally, clear the form fields
    changePasswordForm.reset();
  });
});
