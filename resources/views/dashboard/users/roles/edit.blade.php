@extends('dashboard.layouts.master')
@section('title', 'تعديل الصلاحيات')

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
                <h4 class="content-title mb-0 my-auto">صلاحيات المستخدمين</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/
                    {{ $user->name }}</span>
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
                    <form class="form-horizontal" action="{{ route('dashboard.users.roles.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')


                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">الصلاحيات</label>
                                </div>
                                <div class="col-md-9">
                                    @foreach ($roles as $role)
                                        <div class="form-check d-flex align-items-center gap-3 mb-3 border-bottom pb-2" style="gap: 35px">
                                            <!-- Checkbox Input for Role -->
                                            <input class="form-check-input"
                                                   type="checkbox"
                                                   id="role_{{ $role->id }}"
                                                   name="roles[]"
                                                   value="{{ $role->id }}"
                                                   @checked($user->roles->contains('id', $role->id))>

                                            <label class="form-check-label" for="role_{{ $role->id }}">
                                                {{ $role->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                </div>
                {{-- <div class="form-group mb-0 justify-content-end">
										<div class="checkbox">
											<div class="custom-checkbox custom-control">
												<input type="checkbox" data-checkboxes="mygroup" class="custom-control-input" id="checkbox-2">
												<label for="checkbox-2" class="custom-control-label mt-1">Check me Out</label>
											</div>
										</div>
									</div> --}}
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
    <!--Internal  Chart.bundle js -->
    <script src="{{ URL::asset('assets/plugins/chart.js/Chart.bundle.min.js') }}"></script>
    <!-- Internal Select2.min js -->
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/select2.js') }}"></script>
@endsection
