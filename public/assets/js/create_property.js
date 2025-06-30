document.addEventListener("DOMContentLoaded", () => {
  // Get elements for custom file input
  const fileInput = document.getElementById("property-images");
  const customFileUploadText = document.querySelector(
    ".custom-file-input span"
  );
  const uploadButton = document.querySelector(".custom-file-input .upload-btn");

  // Simulate click on hidden file input when custom button is clicked
  uploadButton.addEventListener("click", () => {
    fileInput.click();
  });

  // Get elements for Main Property Image input
  const mainPropertyImageInput = document.getElementById("main-property-image");
  const mainFileUploadText = document.querySelector(
    "#main-property-image + span"
  ); // Selector for the span next to the main image input
  const mainUploadButton = document.querySelector(
    "#main-property-image ~ .upload-btn"
  ); // Selector for the button next to the main image input // Simulate click on hidden main property image input when its custom button is clicked

  mainUploadButton.addEventListener("click", () => {
    mainPropertyImageInput.click();
  }); // Update the displayed file name for main property image when a file is selected

  mainPropertyImageInput.addEventListener("change", () => {
    if (mainPropertyImageInput.files.length > 0) {
      mainFileUploadText.textContent = mainPropertyImageInput.files[0].name;
    } else {
      mainFileUploadText.textContent = "اختر صورة...";
    }
  }); // (الكود الأصلي لـ property-images و uploadButton.addEventListener سيتبع هنا) // Simulate click on hidden file input when custom button is clicked

  uploadButton.addEventListener("click", () => {
    fileInput.click();
  });

  // Update the displayed file name when a file is selected
  fileInput.addEventListener("change", () => {
    if (fileInput.files.length > 0) {
      if (fileInput.files.length === 1) {
        customFileUploadText.textContent = fileInput.files[0].name;
      } else {
        customFileUploadText.textContent = `${fileInput.files.length} ملفات مختارة`;
      }
    } else {
      customFileUploadText.textContent = "اختر صورة أو أكثر";
    }
  });

  // Optional: Add basic form submission handler
  const submitButton = document.querySelector(".submit-btn");
  submitButton.addEventListener("click", (e) => {
    e.preventDefault(); // Prevent default form submission

    // Here you would collect all form data
    const propertyLocation = document.getElementById("property-location").value;
    const propertyType = document.querySelector(
      'input[name="property-type"]:checked'
    ).value;
    const propertyArea = document.getElementById("property-area").value;
    const propertyPrice = document.getElementById("property-price").value;
    const propertyCurrency = document.getElementById("property-currency").value;
    const detailedAddress = document.getElementById("detailed-address").value;
    const propertyDescription = document.getElementById(
      "property-description"
    ).value;
    const ownerPhone = document.getElementById("owner-phone").value;
    const images = fileInput.files;

    // For demonstration, log the data
    console.log({
      propertyLocation,
      propertyType,
      propertyArea,
      propertyPrice,
      propertyCurrency,
      detailedAddress,
      propertyDescription,
      ownerPhone,
      imagesCount: images.length,
    });

    alert("تم إرسال بيانات العقار بنجاح (هذا مجرد عرض توضيحي)");
    // In a real application, you would send this data to a server using fetch() or XMLHttpRequest
  });
});
