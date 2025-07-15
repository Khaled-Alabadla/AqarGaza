@extends('dashboard.layouts.master')
@section('title', $page->title)

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
                <h4 class="content-title mb-0 my-auto">الصفحات</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ تعديل
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
                    <form class="form-horizontal" action="{{ route('dashboard.pages.update', $page->id) }}" method="POST">
                        @csrf
                        @method('put')
                        <div class="form-group ">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">اسم الصفحة</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" value="{{ old('name', $page->name) }}" class="form-control"
                                        name="name">
                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">العنوان</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" value="{{ old('title', $page->title) }}" class="form-control"
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
                                    <input type="text" value="{{ old('subtitle', $page->subtitle) }}"
                                        class="form-control" name="subtitle">
                                </div>
                            </div>
                        </div>

                        <div class="form-group ">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">المحتوى</label>
                                </div>
                                <div class="col-md-9">
                                    <textarea name="content" class="page-content form-control" rows="3">{{ old('content', $page->content) }}</textarea>
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
                        item.style.setProperty('display', 'none',
                            'important'); // Hide the item with !important
                    }
                });
            });
        });
    </script>
    <!-- jQuery -->

    <!-- Internal Select2.min js -->
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>


@endsection
