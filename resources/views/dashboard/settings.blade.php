@extends('dashboard.layouts.master')
@section('title', 'إعدادات الموقع')

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

        #site-logo {
            position: relative;
            display: inline-block
        }

        #delete-logo {
            position: absolute;
            color: #f00;
            background-color: #fff;
            /* padding: 5px; */
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            left: 0;
            top: 0
        }
    </style>
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الإعدادات</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/
                    إعدادات الموقع</span>
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
                    @if (session('success'))
                        <div class="alert alert-success" id="success-alert">{{ session('success') }}</div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="m-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    {{-- <div class="mb-4 main-content-slabel">Personal Information</div> --}}
                    <form class="form-horizontal" action="{{ route('dashboard.settings') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group ">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">اسم الموقع</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" value="{{ old('site_name', $settings['site_name']) }}"
                                        class="form-control" name="site_name">
                                </div>

                            </div>
                        </div>
                        <div class="form-group ">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">شعار الموقع</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="file" class="form-control" name="site_logo">
                                    @if ($settings['site_logo'] ?? '')
                                        <div id="site-logo">
                                            <img width="90" src="{{ asset($settings['site_logo']) }}" alt="">
                                            <span id="delete-logo">X</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">فيسبوك</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" value="{{ old('facebook', $settings['facebook']) }}"
                                        class="form-control" name="facebook">
                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">تويتر</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" value="{{ old('twitter', $settings['twitter']) }}"
                                        class="form-control" name="twitter">
                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">حقوق النشر</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" value="{{ old('copyright', $settings['copyright']) }}"
                                        class="form-control" name="copyright">
                                </div>
                            </div>
                        </div>
                        <div class="text-right mx-auto my-2">
                            <button type="submit" class="btn btn-primary waves-effect waves-light px-5">حفظ</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('js')
    <!--Internal  Chart.bundle js -->
    <script src="{{ URL::asset('assets/plugins/chart.js/Chart.bundle.min.js') }}"></script>
    <!-- Internal Select2.min js -->
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/select2.js') }}"></script>
    <script>
        let del = document.querySelector('#delete-logo');
        del.onclick = (e) => {
            $.get('/dashboard/delete-logo')
                .done((res) => {
                    e.target.parentElement.remove();
                })
        }
    </script>
@endsection
