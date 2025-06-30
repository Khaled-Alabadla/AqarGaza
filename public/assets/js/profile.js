document.addEventListener("DOMContentLoaded", () => {
  // Profile picture upload functionality
  const profileImageUpload = document.getElementById("profile-image-upload");
  const profileImageDisplay = document.getElementById("profile-image-display");
  const uploadIconWrapper = document.querySelector(".upload-icon-wrapper");

  // Trigger file input when the camera icon wrapper is clicked
  uploadIconWrapper.addEventListener("click", () => {
    profileImageUpload.click();
  });

  // Handle image preview when a file is selected
  profileImageUpload.addEventListener("change", (event) => {
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = (e) => {
        profileImageDisplay.src = e.target.result;
      };
      reader.readAsDataURL(file);
    }
  });

  // Set default date for "تاريخ الميلاد" to today's date
  const dateOfBirthInput = document.getElementById("date-of-birth");
  const today = new Date();
  const year = today.getFullYear();
  const month = String(today.getMonth() + 1).padStart(2, "0"); // Month is 0-indexed
  const day = String(today.getDate()).padStart(2, "0");
  dateOfBirthInput.value = `${year}-${month}-${day}`;

  // Optional: Enable/Disable fields for editing
  const editButton = document.querySelector(".edit-btn");
  const inputFields = document.querySelectorAll(
    ".account-section input:not([readonly]), .account-section select"
  );

  let isEditing = false;

  // Initial state: disable all editable fields
  inputFields.forEach((field) => {
    field.setAttribute("disabled", "true");
  });

  editButton.addEventListener("click", (e) => {
    e.preventDefault();

    if (isEditing) {
      // If currently editing, save changes
      inputFields.forEach((field) => {
        field.setAttribute("disabled", "true");
      });
      editButton.textContent = "تعديل"; // Change button text back to Edit
      editButton.style.backgroundColor = "var(--primary-blue)";
      alert("تم حفظ التعديلات بنجاح.");
      // Here you would typically send updated data to a server
    } else {
      // If not editing, enable fields
      inputFields.forEach((field) => {
        field.removeAttribute("disabled");
      });
      editButton.textContent = "حفظ التعديلات"; // Change button text to Save
      editButton.style.backgroundColor = "#28a745"; // Green for save button
    }
    isEditing = !isEditing; // Toggle editing state
  });
});
