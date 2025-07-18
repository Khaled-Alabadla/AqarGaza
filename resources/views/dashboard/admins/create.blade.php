@extends('dashboard.layouts.master')
@section('title', 'إضافة مسؤول جديد')

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
                <h4 class="content-title mb-0 my-auto">المسؤولين</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/
                    إضافة مسؤول</span>
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
                    <form class="form-horizontal" action="{{ route('dashboard.admins.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">البريد الإلكتروني</label>
                                </div>
                                <select class="select2 col-md-9" name="email">
                                    <option label="Choose one">
                                    </option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->email }}">
                                            {{ $user->email }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="">
                                <div class="">
                                    <label class="form-label">الصلاحيات</label>
                                </div>
                                <div class="">
                                    @foreach ($roles as $role)
                                        <div class="form-check d-flex align-items-center gap-3 mb-3 border-bottom pb-2"
                                            style="gap: 35px">
                                            <!-- Checkbox Input for Role -->
                                            <input class="form-check-input" type="checkbox" id="role_{{ $role->id }}"
                                                name="roles[]" value="{{ $role->id }}">

                                            <label class="form-check-label" for="role_{{ $role->id }}">
                                                {{ $role->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
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
    <!-- Internal Select2.min js -->
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/select2.js') }}"></script>
@endsection
