document.addEventListener('DOMContentLoaded', function () {
    // Handle dropdown toggle
    const optionsButtons = document.querySelectorAll('.options-btn');
    optionsButtons.forEach((button) => {
        button.addEventListener('click', function () {
            const dropdown = this.nextElementSibling;
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        });
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function (event) {
        if (!event.target.closest('.options-dropdown-container')) {
            document.querySelectorAll('.dropdown-content').forEach((dropdown) => {
                dropdown.style.display = 'none';
            });
        }
    });

    // Handle delete button with SweetAlert2
    const deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach((button) => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const propertyId = this.getAttribute('data-property-id');

            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: 'لن تتمكن من استرجاع هذا العقار بعد الحذف!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'نعم، احذفه!',
                cancelButtonText: 'إلغاء',
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('http://aqar-gaza.ct.ws/properties/' + propertyId + '/delete', {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            Accept: 'application/json',
                            'Content-Type': 'application/json', // Add Content-Type
                        },
                    })
                        .then((response) => response.json())
                        .then((data) => {
                            if (data.success) {
                                document.getElementById(`my-property-${propertyId}`).remove();
                                Swal.fire('تم الحذف!', 'تم حذف العقار بنجاح.', 'success');
                            } else {
                                Swal.fire('خطأ!', data.message || 'حدث خطأ أثناء حذف العقار.', 'error');
                            }
                        })
                        .catch((error) => {
                            Swal.fire('خطأ!', 'حدث خطأ أثناء حذف العقار.', 'error');
                        });
                }
            });
        });
    });
});
