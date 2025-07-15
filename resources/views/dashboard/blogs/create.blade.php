@extends('dashboard.layouts.master')
@section('title', 'إضافة منشور جديد')

@section('css')
    <!-- Internal Select2 css -->
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

    <style>
        .form-check {
            margin-bottom: 7px;
        }

        input[type="radio"],
        input[type="checkbox"] {
            position: relative !important;
        }

        .form-check-label {
            width: 40%;
        }
    </style>
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between align-items-center">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">العقارات</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ إضاقة
                    عقار</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    <!-- row -->
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="m-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form class="form-horizontal" action="{{ route('dashboard.blogs.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group ">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">العنوان</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" value="{{ old('title', '') }}" class="form-control"
                                        name="title">
                                </div>
                            </div>
                        </div>

                        <div class="form-group ">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">الصورة</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="file" name="image">
                                </div>
                            </div>
                        </div>

                        <div class="form-group ">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">المحتوى</label>
                                </div>
                                <div class="col-md-9">
                                    <textarea id="content" name="content" class="form-control" placeholder="قم بكتابة المحتوى" rows="5"> </textarea>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary waves-effect waves-light">حفظ</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        // Handle Select All functionality
        const selectAllCheckbox = document.getElementById('select-all');
        const employeeCheckboxes = document.querySelectorAll('.user-checkbox');
        const quantityInputs = document.querySelectorAll('.user-quantity');

        selectAllCheckbox.onclick = function() {
            const isChecked = selectAllCheckbox.checked;
            employeeCheckboxes.forEach((checkbox, index) => {
                checkbox.checked = isChecked;
                quantityInputs[index].readOnly = !isChecked;
                if (isChecked) {
                    quantityInputs[index].value = 1;
                } else {
                    quantityInputs[index].value = 0;
                }
            });
        };

        // Handle individual user checkbox functionality
        employeeCheckboxes.forEach((checkbox, index) => {
            checkbox.onclick = function() {
                if (checkbox.checked) {
                    quantityInputs[index].readOnly = false;
                    quantityInputs[index].value = 1;
                } else {
                    quantityInputs[index].readOnly = true;
                    quantityInputs[index].value = 0;
                }
            };
        });

        // Handle user search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('employeeSearch');
            const employeeItems = document.querySelectorAll('.user-item');

            searchInput.addEventListener('input', function() {
                const searchTerm = searchInput.value.toLowerCase();


                employeeItems.forEach((item) => {
                    const employeeName = item.querySelector('.user-name').textContent
                        .toLowerCase();
                    // console.log(employeeName);

                    if (employeeName.includes(searchTerm)) {
                        item.style.display = 'flex';
                        console.log(employeeName);

                    } else {
                        item.style.setblog('display', 'none',
                            'important'); // Hide the item with !important
                    }
                });
            });
        });
    </script>

    <!-- Internal Select2.min js -->
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.9.1/tinymce.min.js"
        integrity="sha512-09JpfVm/UE1F4k8kcVUooRJAxVMSfw/NIslGlWE/FGXb2uRO1Nt4BXAJ3LxPqNbO3Hccdu46qaBPp9wVpWAVhA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        tinymce.init({
            selector: '#content', // Replace this CSS selector to match the placeholder element for TinyMCE
            plugins: 'preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons accordion markdown math importword exportword exportpdf',
            toolbar: 'undo redo | accordion accordionremove | importword exportword exportpdf | math | blocks fontfamily fontsize | bold italic underline strikethrough | align numlist bullist | link image | table media | lineheight outdent indent| forecolor backcolor removeformat | charmap emoticons | code fullscreen preview | save print | pagebreak anchor codesample | ltr rtl',
            menubar: 'file edit view insert format tools table help'
        });
    </script>

@endsection
