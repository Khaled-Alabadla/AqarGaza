@extends('dashboard.layouts.master')
@section('title', 'تعديل الملف الشخصي ')

@section('css')
    <!-- Internal Select2 css -->
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="/assets/plugins/fileuploads/css/fileupload.css" rel="stylesheet" type="text/css" />
    <!---Internal Fancy uploader css-->
    <link href="/assets/plugins/fancyuploder/fancy_fileupload.css" rel="stylesheet" />
    <link rel="stylesheet" href="/assets/plugins/sumoselect/sumoselect-rtl.css">
    <!--Internal  TelephoneInput css-->
    <link rel="stylesheet" href="/assets/plugins/telephoneinput/telephoneinput-rtl.css">
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">المستخدمين</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ تعديل
                    مستخدم</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    @if (session('success'))
        <div class="alert alert-success" id="success-alert">{{ session('success') }}</div>
    @endif
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
                    <form class="form-horizontal" action="{{ route('dashboard.users.update', $user->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group ">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">اسم المستخدم الرباعي</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="name"
                                        value="{{ old('name', $user->name) }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group ">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">البريد الإلكتروني</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" value="{{ old('email', $user->email) }}" class="form-control"
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
                                    <input type="text" value="{{ old('phone', $user->phone) }}" class="form-control"
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
                                    <input type="text" value="{{ old('address', $user->address) }}" class="form-control"
                                        name="address">
                                </div>

                            </div>
                        </div>

                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary waves-effect waves-light">تعديل</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="/assets/plugins/select2/js/select2.min.js"></script>
    <!--Internal Fileuploads js-->
    <script src="/assets/plugins/fileuploads/js/fileupload.js"></script>
    <script src="/assets/plugins/fileuploads/js/file-upload.js"></script>
    <!--Internal Fancy uploader js-->
    <script src="/assets/plugins/fancyuploder/jquery.ui.widget.js"></script>
    <script src="/assets/plugins/fancyuploder/jquery.fileupload.js"></script>
    <script src="/assets/plugins/fancyuploder/jquery.iframe-transport.js"></script>
    <script src="/assets/plugins/fancyuploder/jquery.fancy-fileupload.js"></script>
    <script src="/assets/plugins/fancyuploder/fancy-uploader.js"></script>
    <!--Internal  Form-elements js-->
    <script src="/assets/js/advanced-form-elements.js"></script>
    <script src="/assets/js/select2.js"></script>
    <!--Internal Sumoselect js-->
    <script src="/assets/plugins/sumoselect/jquery.sumoselect.js"></script>
    <!--Internal  Datepicker js -->
    <script src="/assets/plugins/jquery-ui/ui/widgets/datepicker.js"></script>
    <!--Internal  jquery.maskedinput js -->
    <script src="/assets/plugins/jquery.maskedinput/jquery.maskedinput.js"></script>
    <!--Internal  spectrum-colorpicker js -->
    <script src="/assets/plugins/spectrum-colorpicker/spectrum.js"></script>
    <!-- Internal form-elements js -->
    <script src="/assets/js/form-elements.js"></script>
@endsection
