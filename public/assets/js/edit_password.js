document.addEventListener('DOMContentLoaded', () => {
    // Password Visibility Toggle
    document.querySelectorAll('.toggle-password').forEach((icon) => {
        icon.addEventListener('click', (event) => {
            const targetId = event.target.dataset.target;
            const passwordInput = document.getElementById(targetId);

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                event.target.classList.replace('far', 'fas'); // Change to solid eye icon
                event.target.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                event.target.classList.replace('fas', 'far'); // Change to outline eye icon
                event.target.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    });

    // Form Submission Handler
    const changePasswordForm = document.getElementById('changePasswordForm');
    changePasswordForm.addEventListener('submit', (e) => {
        const newPassword = document.getElementById('new-password').value;
        const confirmPassword = document.getElementById('confirm-password').value;
    });
});
