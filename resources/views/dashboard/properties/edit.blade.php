@extends('dashboard.layouts.master')
@section('title', 'تعديل عقار ')

@section('css')
    <!-- Internal Select2 css -->
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <style>
        .form-check {
            margin-bottom: 7px
        }

        input[type="radio"],
        input[type="checkbox"] {
            position: relative !important;
        }

        .form-check-label {
            width: 40%
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
                    {{-- <div class="mb-4 main-content-label">Personal Information</div> --}}
                    <form class="form-horizontal" action="{{ route('dashboard.properties.update', $property->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group ">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">نوع العقار</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" value="{{ old('type', $property->type) }}" class="form-control"
                                        name="type">
                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">الكمية</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="number" value="{{ old('quantity', $property->quantity) }}"
                                        class="form-control" name="quantity">
                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">الجهة المانحة</label>
                                </div>
                                <div class="col-md-9">
                                    <select class="form-control select2" name="donor_id">
                                        @foreach ($properties as $property)
                                            <option value="{{ $property->id }}"
                                                {{ old('donor_id', $property->property->id) == $property->id ? 'selected' : '' }}>
                                                {{ $property->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="mb-4 main-content-label">Name</div> --}}

                        <div class="form-group ">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">تاريخ الوصول</label>
                                </div>
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                            </div>
                                        </div>
                                        <input class="form-control fc-datepicker" name="date"
                                            value="{{ old('date', $property->date) }}" placeholder="Month/Day/Year"
                                            type="text">
                                    </div><!-- wd-200 -->
                                </div>

                            </div>
                        </div>
                        <div class="form-group ">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">ملاحظات</label>
                                </div>
                                <div class="col-md-9">
                                    <textarea name="notes" class="form-control" placeholder="أدخل أي ملاحظات" rows="3">{{ old('notes', $property->notes) }}</textarea>
                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">المستخدمين المستلمين</label>
                                </div>
                                <div class="col-md-9">

                                    @foreach ($users as $user)
                                        <div
                                            class="form-check d-flex align-items-center justify-content-between gap-3 mb-3 border-bottom pb-2">
                                            <!-- Checkbox Input -->
                                            <input class="form-check-input user-checkbox" type="checkbox"
                                                id="user-{{ $user->id }}"
                                                name="users[{{ $user->id }}][selected]"
                                                value="{{ $user->id }}" placeholder="الكمية"
                                                data-quantity-id="quantity-{{ $user->id }}"
                                                @checked($property->distributes->contains('user_id', $user->id))>

                                            <!-- Employee Name -->
                                            <label class="form-check-label" for="user-{{ $user->id }}">
                                                {{ $user->name }}
                                            </label>

                                            <label for="quantity-{{ $user->id }}">الكمية المستلمة</label>
                                            <!-- Quantity Input -->
                                            <input type="number" class="form-control user-quantity" style="width: 20%"
                                                id="quantity-{{ $user->id }}"
                                                name="users[{{ $user->id }}][quantity]" min="1"
                                                placeholder="الكمية"
                                                value="{{ $property->distributes->where('user_id', $user->id)->first()->quantity ?? 0 }}"
                                                readonly>
                                        </div>
                                    @endforeach
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
    <script>
        let checkboxes = document.querySelectorAll('.user-checkbox');
        checkboxes.forEach((checkbox) => {
            checkbox.onchange = function() {
                let input = document.getElementById(this.dataset.quantityId);
                if (this.checked) {
                    input.value = 1;
                    input.removeAttribute('readonly');
                } else {
                    input.value = 0;
                    input.setAttribute('readonly', true)
                }
            }
        })

        let quantityInputs = document.querySelectorAll('.user-quantity');
        quantityInputs.forEach((input) => {

            if(input.value != '0') {
                input.removeAttribute('readonly')
            }
        })
    </script>
@endsection
