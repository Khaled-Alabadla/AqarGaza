document.addEventListener("DOMContentLoaded", () => {
  const optionsButtons = document.querySelectorAll(".options-btn");

  optionsButtons.forEach((button) => {
    button.addEventListener("click", (event) => {
      event.stopPropagation();

      const dropdownContainer = event.currentTarget.closest(
        ".options-dropdown-container"
      );
      document
        .querySelectorAll(".options-dropdown-container.active")
        .forEach((openDropdown) => {
          if (openDropdown !== dropdownContainer) {
            openDropdown.classList.remove("active");
          }
        });

      dropdownContainer.classList.toggle("active");
    });
  });

  document.addEventListener("click", (event) => {
    document
      .querySelectorAll(".options-dropdown-container.active")
      .forEach((openDropdown) => {
        if (!openDropdown.contains(event.target)) {
          openDropdown.classList.remove("active");
        }
      });
  });

  // **  زر الحذف **
  const deleteButtons = document.querySelectorAll(".delete-btn");

  deleteButtons.forEach((button) => {
    button.addEventListener("click", (event) => {
      event.preventDefault();
      event.stopPropagation();

      const propertyId = event.currentTarget.dataset.propertyId;
      const propertyCard = document.getElementById(propertyId);

      if (confirm(`هل أنت متأكد أنك تريد حذف هذا العقار (${propertyId})؟`)) {
        if (propertyCard) {
          propertyCard.remove();
          alert("تم حذف العقار بنجاح!");
          console.log(`Property ${propertyId} has been deleted.`);
        }
      }
      const dropdownContainer = event.currentTarget.closest(
        ".options-dropdown-container"
      );
      if (dropdownContainer) {
        dropdownContainer.classList.remove("active");
      }
    });
  });

  // **  زر التعديل **
  const editButtons = document.querySelectorAll(".edit-btn");

  editButtons.forEach((button) => {
    button.addEventListener("click", (event) => {
      event.stopPropagation();

      const propertyId = event.currentTarget.closest(".property-card").id;
      console.log(`Redirecting to edit page for property: ${propertyId}`);
    });
  });

  const favoriteButtons = document.querySelectorAll(".favorite-btn");
  favoriteButtons.forEach((button) => {
    button.addEventListener("click", (event) => {
      event.stopPropagation();
      const icon = event.currentTarget.querySelector("i");
      icon.classList.toggle("far");
      icon.classList.toggle("fas");
      event.currentTarget.classList.toggle("active");
      console.log("Favorite status toggled for this property card.");
    });
  });
});
