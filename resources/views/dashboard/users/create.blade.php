@extends('dashboard.layouts.master')
@section('title', 'إضافة مستخدم جديد')

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">المستخدمين</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ إضاقة
                    مستخدم</span>
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
                    <form class="form-horizontal" action="{{ route('dashboard.users.store') }}" method="POST">
                        @csrf
                        <div class="form-group ">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">اسم المستخدم الرباعي (مطلوب)</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" value="{{ old('name', '') }}" class="form-control" name="name">
                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">البريد الإلكتروني (مطلوب)</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="email" value="{{ old('email', '') }}" class="form-control"
                                        name="email">
                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">رقم الجوال</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" value="{{ old('phone', '') }}" class="form-control"
                                        name="phone">
                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">العنوان</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" value="{{ old('address', '') }}" class="form-control"
                                        name="address">
                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">كلمة المرور (مطلوب)</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="password" class="form-control" name="password">
                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">تأكيد كلمة المرور (مطلوب)</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="password" value="" class="form-control" name="password_confirmation">
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary waves-effect waves-light">إضافة</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
