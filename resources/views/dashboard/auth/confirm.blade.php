@extends('dashboard.layouts.master')
@section('title', 'تأكيد كلمة المرور')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/verify_styles.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/forget_styles.css') }}"> --}}
    <style>
        body {
            font-family: "Cairo", sans-serif !important;
        }
    </style>
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">كلمة المرور</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/
                    تأكيد كلمة المرور</span>
            </div>
        </div>

    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    <!-- row opened -->
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card mg-b-20">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mg-b-0">تأكيد كلمة المرور</h4>
                    </div>

                    {{-- <p class="tx-12 tx-gray-500 mb-2">Example of Valex Bordered Table.. <a href="">Learn more</a></p> --}}
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" id="success-alert">{{ session('success') }}</div>
                    @endif
                    <div class="verify">
                        <div class="verify-email-container">
                            <div class="verify-email-card">

                                <div class="mail-icon-wrapper">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <h1 class="title">تأكيد كلمة المرور</h1>
                                <p class="description">من فضلك، أدخل كلمة المرور للدخول</p>
                                <form style="width: 100%" action="{{ route('password.confirm') }}" method="POST">
                                    @csrf
                                    <div>
                                        <input class="" type="password" id="password" name="password"
                                            placeholder="أدخل كلمة المرور" required style="margin-block: 10px">
                                        @error('password')
                                            <small
                                                style="    color: red;text-align: right;display: block;margin-bottom: 20px;">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <button style="color: white; outline: none" class="resend-btn">تأكيد</ه>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- /row -->
    </div>
    <!-- Container closed -->
    </div>
    <div class="modal" id="modaldemo1">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">حذف الصلاحية</h6><button aria-label="Close" class="close" data-dismiss="modal"
                        type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    {{-- <h6>Modal Body</h6> --}}
                    <p>هل أنت متأكد من عملية الحذف؟</p>
                </div>
                <div class="modal-footer">
                    <form action="" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn ripple btn-danger">حفظ التغييرات</button>
                    </form>
                    <button class="btn ripple btn-secondary" data-dismiss="modal" type="button">إغلاق</button>
                </div>

            </div>
        </div>
    </div>
    <!-- main-content closed -->
@endsection
