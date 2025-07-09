@extends('dashboard.layouts.master')
@section('title', 'إضافة صفحة جديدة')

@section('css')
    <!-- Internal Select2 css -->
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/amazeui-datetimepicker/css/amazeui.datetimepicker.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/jquery-simple-datetimepicker/jquery.simple-dtpicker.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/pickerjs/picker.min.css') }}" rel="stylesheet">

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
                <h4 class="content-title mb-0 my-auto">الصفحات</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ إضاقة
                    صقحة</span>
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
                    <form class="form-horizontal" action="{{ route('dashboard.pages.store') }}" method="POST">
                        @csrf
                        <div class="form-group ">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">اسم الصفحة</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" value="{{ old('name', '') }}" class="form-control" name="name">
                                </div>
                            </div>
                        </div>
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
                                    <label class="form-label">العنوان الفرعي</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" value="{{ old('subtitle', '') }}" class="form-control"
                                        name="subtitle">
                                </div>
                            </div>
                        </div>

                        <div class="form-group ">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">المحتوى</label>
                                </div>
                                <div class="col-md-9">
                                    <textarea name="content" class="page-content form-control" rows="3"> </textarea>
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
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: 'textarea#myeditorinstance', // Replace this CSS selector to match the placeholder element for TinyMCE
            plugins: 'preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons accordion markdown math importword exportword exportpdf',
            toolbar: 'undo redo | accordion accordionremove | importword exportword exportpdf | math | blocks fontfamily fontsize | bold italic underline strikethrough | align numlist bullist | link image | table media | lineheight outdent indent| forecolor backcolor removeformat | charmap emoticons | code fullscreen preview | save print | pagebreak anchor codesample | ltr rtl',
            menubar: 'file edit view insert format tools table help'
        });
    </script>
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
                        item.style.setProperty('display', 'none',
                            'important'); // Hide the item with !important
                    }
                });
            });
        });
    </script>
    <!-- jQuery -->

    <!--Internal  Datepicker js -->
    <script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
    <!--Internal  jquery.maskedinput js -->
    <script src="{{ URL::asset('assets/plugins/jquery.maskedinput/jquery.maskedinput.js') }}"></script>
    <!--Internal  spectrum-colorpicker js -->
    <script src="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.js') }}"></script>
    <!-- Internal Select2.min js -->
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <!--Internal Ion.rangeSlider.min js -->
    <script src="{{ URL::asset('assets/plugins/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
    <!--Internal  jquery-simple-datetimepicker js -->
    <script src="{{ URL::asset('assets/plugins/amazeui-datetimepicker/js/amazeui.datetimepicker.min.js') }}"></script>
    <!-- Ionicons js -->
    <script src="{{ URL::asset('assets/plugins/jquery-simple-datetimepicker/jquery.simple-dtpicker.js') }}"></script>
    <!--Internal  pickerjs js -->
    <script src="{{ URL::asset('assets/plugins/pickerjs/picker.min.js') }}"></script>
    <!-- Internal form-elements js -->
    <script src="{{ URL::asset('assets/js/form-elements.js') }}"></script>


@endsection
