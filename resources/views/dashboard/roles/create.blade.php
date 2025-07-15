@extends('dashboard.layouts.master')
@section('title', 'إضافة صلاحية جديدة')

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
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الصلاحيات</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ إضاقة
                    صلاحية</span>
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
                    <form class="form-horizontal" action="{{ route('dashboard.roles.store') }}" method="POST">
                        @csrf
                        <div class="form-group ">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">اسم الصلاحية </label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" value="{{ old('name', '') }}" class="form-control" name="name">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">ما الذي يمكنه القيام به؟</label>
                                </div>
                                <div class="col-md-9">
                                    @foreach ($abilities as $ability => $ability_label)
                                        <div class="form-check d-flex align-items-center gap-3 mb-3 border-bottom pb-2"
                                            style="gap: 35px">
                                            <!-- Checkbox Input -->
                                            <input class="form-check-input" type="checkbox" id="{{ $ability }}"
                                                name="abilities[{{ $ability }}] "
                                                @if (is_array(old('abilities')) && in_array($ability, array_keys(old('abilities')))) checked @endif value="{{ $ability }}"
                                                placeholder="الكمية">

                                            <label class="form-check-label" for="{{ $ability }}">
                                                {{ $ability_label }}
                                            </label>

                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="text-right mx-auto my-2">
                            <button type="submit" class="btn btn-primary waves-effect waves-light px-5">إضافة</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('js')

    <!-- Internal Select2.min js -->
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/select2.js') }}"></script>
@endsection
