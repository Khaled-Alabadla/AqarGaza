@extends('dashboard.layouts.master')
@section('title', 'تعديل الملف الشخصي ')

@section('css')
<!-- Internal Select2 css -->
<link href="{{URL::asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
@endsection
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">المستخدمين</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ تعديل مستخدم</span>
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
                            <form class="form-horizontal" action="{{ route('dashboard.users.update', $user->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-group ">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="form-label">اسم المستخدم الرباعي</label>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="name" value="{{ old('name', $user->name) }}"  >
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="form-label">البريد الإلكتروني</label>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" value="{{ old('email', $user->email) }}" class="form-control" name="email"  >
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="form-label">رقم الجوال</label>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="phone" value="{{ old('phone', $user->phone) }}" >
                                        </div>
                                    </div>
                                </div>
                                   <div class="form-group ">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="form-label">عنوان السكن</label>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="address" value="{{ old('address', $user->address) }}" >
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
<!--Internal  Chart.bundle js -->
<script src="{{URL::asset('assets/plugins/chart.js/Chart.bundle.min.js')}}"></script>
<!-- Internal Select2.min js -->
<script src="{{URL::asset('assets/plugins/select2/js/select2.min.js')}}"></script>
<script src="{{URL::asset('assets/js/select2.js')}}"></script>
@endsection
