document.addEventListener('DOMContentLoaded', () => {
    // Profile picture upload functionality
    const profileImageUpload = document.getElementById('profile-image-upload');
    const profileImageDisplay = document.getElementById('profile-image-display');
    const uploadIconWrapper = document.querySelector('.upload-icon-wrapper');

    //     // Trigger file input when the camera icon wrapper is clicked
    //     uploadIconWrapper.addEventListener('click', () => {
    //         profileImageUpload.click();
    //     });

    // Handle image preview when a file is selected
    profileImageUpload.addEventListener('change', (event) => {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                profileImageDisplay.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
});
