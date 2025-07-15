@extends('dashboard.layouts.master')
@section('title')
    {{ $property->title }}
@endsection

@section('css')
    <!-- Internal Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">العقارات</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/
                    عرض التفاصيل </span>
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
                        <h4 class="card-title mg-b-0">العقارات</h4>
                        <i class="mdi mdi-dots-horizontal text-gray"></i>
                    </div>

                    {{-- <p class="tx-12 tx-gray-500 mb-2">Example of Valex Bordered Table.. <a href="">Learn more</a></p> --}}
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" id="success-alert">{{ session('success') }}</div>
                    @endif
                    <h3 class="mb-3">{{ $property->category->name }} - {{ $property->title }}
                    </h3>

                    <div class="row row-sm">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-body h-100">
                                    <div class="row row-sm ">
                                        <div class=" col-xl-5 col-lg-12 col-md-12">
                                            <div class="preview-pic tab-content">
                                                <div class="tab-pane active" id="pic-1"><img
                                                        src="{{ asset($property->main_image) }}" alt="image"></div>
                                                @foreach ($property->images as $image)
                                                    <div class="tab-pane" id="pic-2"><img
                                                            src="{{ asset($image->image_path) }}" alt="image"></div>
                                                @endforeach
                                            </div>
                                            <ul class="preview-thumbnail nav nav-tabs">
                                                <li class="active"><a data-target="#pic-1" data-toggle="tab"><img
                                                            src="http://127.0.0.1:8001/assets/img/ecommerce/shirt-5.png"
                                                            alt="image"></a></li>
                                                <li><a data-target="#pic-2" data-toggle="tab"><img
                                                            src="http://127.0.0.1:8001/assets/img/ecommerce/shirt-2.png"
                                                            alt="image"></a></li>
                                                <li><a data-target="#pic-3" data-toggle="tab"><img
                                                            src="http://127.0.0.1:8001/assets/img/ecommerce/shirt-3.png"
                                                            alt="image"></a></li>
                                                <li><a data-target="#pic-4" data-toggle="tab"><img
                                                            src="http://127.0.0.1:8001/assets/img/ecommerce/shirt-4.png"
                                                            alt="image"></a></li>
                                                <li><a data-target="#pic-5" data-toggle="tab"><img
                                                            src="http://127.0.0.1:8001/assets/img/ecommerce/shirt-1.png"
                                                            alt="image"></a></li>
                                            </ul>
                                        </div>
                                        <div class="details col-xl-7 col-lg-12 col-md-12 mt-4 mt-xl-0">
                                            <h4 class="product-title">{{ $property->category->name }}</h4>
                                            <p class="text-muted tx-13 mt-2">{{ $property->title }}</p>

                                            <div class="price h5 ml-2 mt-3">السعر:<span
                                                    class="h5 mr-2">{{ $price }}</span>
                                            </div>

                                            <div class="sizes d-flex align-items-center mt-3">النوع:
                                                <span class="size d-flex" data-toggle="tooltip" title=""
                                                    data-original-title="small"><label class="rdiobox mb-0"><input
                                                            checked="" name="type" type="radio"
                                                            @disabled($property->type != 'sale') @checked($property->type == 'sale')> <span
                                                            class="font-weight-bold">بيع</span></label></span>
                                                <span class="size d-flex" data-toggle="tooltip" title=""
                                                    data-original-title="medium"><label class="rdiobox mb-0"><input
                                                            name="type" type="radio" @disabled($property->type != 'rent')
                                                            @checked($property->type == 'rent')>
                                                        <span>تأجير</span></label></span>

                                            </div>
                                            @if ($property->area)
                                                <div class="colors d-flex align-items-center mt-3">
                                                    <span class="ml-2 h5">المساحة:</span>
                                                    <span class="h5">{{ $property->area }} م<sup>2</sup></span>
                                                </div>
                                            @endif
                                            @if ($property->rooms)
                                                <div class="colors d-flex align-items-center mt-3">
                                                    <p class="h5">عدد الغرف:</p>
                                                    <span class="h5 mr-1">{{ $property->rooms }}</span>
                                                </div>
                                            @endif
                                            @if ($property->bathrooms)
                                                <div class="colors d-flex align-items-center mt-3">
                                                    <span class="h5">عدد الحمامات:</span>
                                                    <span class="h5 mr-1">{{ $property->bathrooms }}</span>
                                                </div>
                                            @endif
                                            <div class="mt-3">
                                                <p>تفاصيل العقار: </p>
                                                <p class="product-description mt-1">{{ $property->description }}</p>
                                            </div>


                                        </div>
                                    </div>
                                </div>
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
    <!-- main-content closed -->
@endsection
@section('js')
    <!-- Internal Data tables -->
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
    <!--Internal  Datatable js -->
    <script src="{{ URL::asset('assets/js/table-data.js') }}"></script>
    <script>
        // Function to hide alert after 5 seconds
        function hideAlert(id) {
            setTimeout(function() {
                let alertElement = document.getElementById(id);
                if (alertElement) {
                    // alertElement.style.visibility='hidden';
                    alertElement.style.opacity = 0;
                    alertElement.style.maxHeight = 0;
                    alertElement.style.padding = 0;
                    alertElement.style.marginBottom = 0
                }
            }, 5000);
        }

        @if (session('success'))
            hideAlert('success-alert');
        @endif
    </script>
    <script>
        $('#modaldemo1').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var assistanceId = button.data('id'); // Extract the ID from data-* attributes
            var modal = $(this);
            modal.find('form').attr('action', '/properties/' + assistanceId);
        });
    </script>
@endsection
