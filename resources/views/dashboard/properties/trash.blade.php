@extends('dashboard.layouts.master')
@section('title', 'عرض قائمة العقارات المحذوفة')

@section('css')
    <!-- Internal Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <style>
        thead tr th,
        tbody tr td {
            display: table-cell !important
        }

        table#example {
            width: 100% !important;
            margin: 0
        }

        .alert {
            /* visibility: visible; */
            transition: all 0.5s ease;
            /* margin: 0 !important */
        }

        .buttons-pdf {
            display: none !important
        }
    </style>
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">العقارات</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/
                    العقارات المحذوفة</span>
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
                    <div class="table-responsive">
                        <table id="example2" class="table key-buttons text-md-nowrap">
                            <thead>
                                <tr>
                                    <th class="border-bottom-0">#</th>
                                    <th class="border-bottom-0">نوع العقار</th>
                                    <th class="border-bottom-0">بيع \ تأجير</th>
                                    <th class="border-bottom-0">العنوان</th>
                                    <th class="border-bottom-0">السعر</th>
                                    <th class="border-bottom-0">حالة العقار</th>
                                    <th class="border-bottom-0">التاريخ</th>
                                    @if (auth()->user()->can('properties.delete'))
                                        <th>العمليات</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($properties as $property)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $property->category->name }}</td>
                                        <td>{{ $property->type == 'rent' ? 'تأجير' : 'بيع' }}</td>
                                        <td>{{ $property->title }}</td>
                                        <td>{{ $property->price . ($property->currency == 'ILS' ? 'شيكل ' : ' دولار') }}
                                        </td>
                                        <td>{{ (($property->status == 'available' ? 'متوفر' : $property->status == 'rented') ? '' : $property->status == 'sold') ? 'تم البيع' : 'تم التأجير' }}
                                        </td>
                                        <td>{{ date_format(date_create($property->created_at), 'd/m/Y') }}</td>

                                        @if (auth()->user()->can('properties.restore') || auth()->user()->can('properties.force_delete'))
                                            <td>
                                                @can('properties.restore')
                                                    <a href="{{ route('dashboard.properties.restore', $property->id) }}"
                                                        class="btn btn-primary-gradient btn-sm"> استعادة</i></a>
                                                @endcan

                                                @can('properties.force_delete')
                                                    <a class="btn btn-danger-gradient btn-sm" data-target="#modaldemo1"
                                                        data-toggle="modal" href="" data-id="{{ $property->id }}">حذف
                                                        نهائي</a>
                                                @endcan

                                            </td>
                                        @endif
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="modaldemo1">
            <div class="modal-dialog" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title">حذف العقار</h6><button aria-label="Close" class="close"
                            data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        {{-- <h6>Modal Body</h6> --}}
                        <p>هل أنت متأكد من عملية حذف العقار بشكل نهائي؟</p>
                    </div>
                    <div class="modal-footer">
                        <form action="" method="POST" class="mb-0">
                            @csrf
                            @method('DELETE')
                            <button class="btn ripple btn-danger">حفظ التغييرات</button>
                        </form>
                        <button class="btn ripple btn-secondary" data-dismiss="modal" type="button">إغلاق</button>
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
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/table-data.js') }}"></script>
    <script src="/assets/plugins/datatable/js/dataTables.bootstrap4.js"></script>

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
            var propertyId = button.data('id'); // Extract the ID from data-* attributes
            var modal = $(this);
            modal.find('form').attr('action', '/dashboard/properties/' + propertyId + '/force-delete');
        });
    </script>
@endsection
0
