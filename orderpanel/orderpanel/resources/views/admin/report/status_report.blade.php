@extends('admin.master')
@section('title', 'Order Status Report')
@section('content')
@push('css')
    <link rel="stylesheet" href="{{ asset('admin/DataTables/datatables.min.css') }}">
<style>
    .img-responsive {
        float: left;
        border: 2px solid #ddd;
    }

    .product-title {
        width: 100%;
        display: block;
        height: 30px;
        overflow: hidden;
    }

    .status_active {
           background: #FE19B4 !important;
           border: none;
       }

       .order_status {
           width: 18.9%;
           background: #6A00A8;
           font-weight: bold;
           border: none;
           margin: 4px;
       }

       .btn .badge {
           position: relative;
           top: 2px;
           text-align: center;
           float: right;
           color: red;
       }

       @media (max-width: 776px) {
           .order_status {
               width: 48%;
               margin-bottom: 8px;
               background: #6a00a8;
               font-weight: bold;
               border: none;
               margin: 2px;
           }

           .btn .badge {
               position: relative;
               top: 2px;
               text-align: center;
               float: right;
               color: red;
           }
       }
</style>
@endpush
<div class="content-wrapper" style="min-height: 57px;">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Order Status Report</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="https://www.dhakabaazar.com/adminpanel" style="color:black">Home</a></li>
                        <li class="breadcrumb-item active">Order Status Report</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <section class="content">
        <div class="container-fluid">
            <form action="https://www.dhakabaazar.com/adminpanel/admin/orderStatus/report" method="get">
                <div class="row" style="cursor: pointer;background: white;margin-bottom: 9px;border: 2px solid #ddd;">
                    <div class="col-6 col-lg-3">
                        <div class="form-group"> <label>Order Status</label>
                            <select name="order_status" id="order_status" class="form-control">
                                <option value="">--Select--</option>
                                <option value="1">New</option>
                                <option value="2">Pending for Payment</option>
                                <option value="3">Pending</option>
                                <option value="4">Courier</option>
                                <option value="5">Invoice</option>
                                <option value="6">Pending Invoice</option>
                                <option value="7">Delivered</option>
                                <option value="8">Booking</option>
                                <option value="9">Return</option>
                                <option value="10">Cancelled</option>
                              </select>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="form-group">
                            <div class="form-group"> <label>Starting Date </label> <input type="date" id="starting_date" name="starting_date" class="form-control"> </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="form-group"> <label>Ending Date </label> <input type="date" id="ending_date" name="ending_date" class="form-control"> </div>
                    </div>
                    <div class="col-6 col-lg-2">
                        <div class="form-group"> <label>Order ID</label> <input type="text" id="order_id" placeholder="Order ID" name="order_id" value="" class="form-control"> </div>
                    </div>
                    <div class="col-6 col-lg-1"> <br>
                        <div class="form-group"> <input type="submit" style="margin-top: 8px;" value="Search" id="search" class="form-control btn btn-success"> </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="row">
            <div class="col-sm-6 col-md-4">
                <button onclick="getTotalProducts(1)" type="button" class="btn btn-success form-control "> Total Order <span class="badge badge-light">{{$offlineOrder+$onlineOrder}} </span>
                </button>
            </div>
            <div class="col-sm-6 col-md-4">
                <button  onclick="getOnlineOrder('guest')" type="button" class="btn btn-info form-control "> Online Order <span class="badge badge-light"> {{$onlineOrder}}</span>
                </button>
            </div>
            <div class="col-sm-6 col-md-4">
                <button onclick="getOnlineOrder('office')" type="button" class="btn btn-primary form-control "> Staff Order <span class="badge badge-light"> {{$offlineOrder}}</span>
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-lg-12 col-xl-12">
                <button onclick="orderStatus(1)" type="button" class="btn btn-primary order_status  "> New <span class="badge badge-light">{{ $new ?? '0' }}</span>
                </button>
                <button onclick="orderStatus(2)" type="button" class="btn btn-primary order_status "> Pending Pyment <span class="badge badge-light"> {{ $pendingPayment ?? '0' }}</span>
                </button>
                <button onclick="orderStatus(3)" type="button" class="btn btn-primary order_status "> Pending <span class="badge badge-light"> {{ $pending ?? '0' }}</span>
                </button>
                <button onclick="orderStatus(4)" type="button" class="btn btn-primary order_status "> Courier <span class="badge badge-light"> {{ $courier ?? '0' }}</span>
                </button>
                <button onclick="orderStatus(5)" type="button" class="btn btn-primary order_status "> Invoice <span class="badge badge-light"> {{ $invoice ?? '0' }}</span>
                </button>
                <button onclick="orderStatus(6)" type="button" class="btn btn-primary order_status "> Pending Invoice <span class="badge badge-light"> {{ $pendingInvoice ?? '0'}}</span>
                </button>
                <button onclick="orderStatus(7)" type="button" class="btn btn-primary order_status "> Delivered <span class="badge badge-light"> {{ $delivered ?? '0' }}</span>
                </button>
                <button onclick="orderStatus(8)" type="button" class="btn btn-primary order_status "> Booking <span class="badge badge-light"> {{ $booking ?? '0' }}</span>
                </button>
                <button onclick="orderStatus(9)" type="button" class="btn btn-primary order_status "> Return <span class="badge badge-light"> {{ $return ?? '0' }}</span>
                </button>
                <button onclick="orderStatus(10)" type="button" class="btn btn-primary order_status "> Cancel <span class="badge badge-light"> {{ $cancelled ?? '0' }}</span>
                </button>
              </div>
        </div>
        <table id="data_table" class="table table-bordered table-striped data-table table-hover">
            <thead>
                <tr>
                    <th>SN</th>
                    <th>Order ID</th>
                    <th>Courier Information</th>
                    <th>Office Staff</th>
                    <th>Customer</th>
                    <th>Products</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </section>
</div>
@push('js')
    <script src="{{ asset('admin/DataTables/datatables.min.js') }}"></script>
<script>
        function orderStatus(id) {
            var id = id;

            $.ajaxSetup({
            headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                }
            });

            var table =  $('#data_table').DataTable({
            order: [],
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            processing: true,
            serverSide: true,
            bDestroy: true,

            ajax: {
                url: "{{route('status-wise-product')}}",
                type: "POST",
                data:{
                    id:id,
                },
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false},
                {data: 'orderNumber', name: 'orderNumber'},
                {data: 'courier', name: 'courier'},
                {data: 'officeStaff', name: 'officeStaff'},
                {data: 'customer', name: 'customer'},
                {data: 'product', name: 'product'},
                {data: 'amount', name: 'amount'},
                {data: 'status', name: 'status'},
                {data: 'action', searchable: false, orderable: false}
                ],
                dom: "<'row'<'col-sm-2'l><'col-sm-7 text-center'B><'col-sm-3'f>>tipr",
                buttons: [
                        {
                            extend: 'copy',
                            className: 'btn-sm btn-info',
                            title: 'Order List',
                            header: true,
                            footer: true,
                            exportOptions: {
                                columns: ['0,1,2,3'],
                            }
                        },
                        {
                            extend: 'csv',
                            className: 'btn-sm btn-success',
                            title: 'Order List',
                            header: true,
                            footer: true,
                            exportOptions: {
                                columns: ['0,1,2,3'],
                            }
                        },
                        {
                            extend: 'excel',
                            className: 'btn-sm btn-dark',
                            title: 'Order List',
                            header: true,
                            footer: true,
                            exportOptions: {
                                columns: ['0,1,2,3'],
                            }
                        },
                        {
                            extend: 'pdf',
                            className: 'btn-sm btn-primary',
                            title: 'Order List',
                            pageSize: 'A2',
                            header: true,
                            footer: true,
                            exportOptions: {
                                columns: ['0,1,2,3'],
                            }
                        },
                        {
                            extend: 'print',
                            className: 'btn-sm btn-danger',
                            title: 'Order List',
                            pageSize: 'A2',
                            header: true,
                            footer: true,
                            orientation: 'landscape',
                            exportOptions: {
                                columns: ['0,1,2,3'],
                                stripHtml: false
                            }
                        }
                    ],
            });
        }

 function getOnlineOrder(type) {
            var buyer_type = type;

            $.ajaxSetup({
            headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                }
            });

            var table =  $('#data_table').DataTable({
            order: [],
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            processing: true,
            serverSide: true,
            bDestroy: true,

            ajax: {
                url: "{{route('status-wise-product')}}",
                type: "POST",
                data:{
                    buyer_type:buyer_type,
                },
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false},
                {data: 'orderNumber', name: 'orderNumber'},
                {data: 'courier', name: 'courier'},
                {data: 'officeStaff', name: 'officeStaff'},
                {data: 'customer', name: 'customer'},
                {data: 'product', name: 'product'},
                {data: 'amount', name: 'amount'},
                {data: 'status', name: 'status'},
                {data: 'action', searchable: false, orderable: false}
                ],
                dom: "<'row'<'col-sm-2'l><'col-sm-7 text-center'B><'col-sm-3'f>>tipr",
                buttons: [
                        {
                            extend: 'copy',
                            className: 'btn-sm btn-info',
                            title: 'Order List',
                            header: true,
                            footer: true,
                            exportOptions: {
                                columns: ['0,1,2,3'],
                            }
                        },
                        {
                            extend: 'csv',
                            className: 'btn-sm btn-success',
                            title: 'Order List',
                            header: true,
                            footer: true,
                            exportOptions: {
                                columns: ['0,1,2,3'],
                            }
                        },
                        {
                            extend: 'excel',
                            className: 'btn-sm btn-dark',
                            title: 'Order List',
                            header: true,
                            footer: true,
                            exportOptions: {
                                columns: ['0,1,2,3'],
                            }
                        },
                        {
                            extend: 'pdf',
                            className: 'btn-sm btn-primary',
                            title: 'Order List',
                            pageSize: 'A2',
                            header: true,
                            footer: true,
                            exportOptions: {
                                columns: ['0,1,2,3'],
                            }
                        },
                        {
                            extend: 'print',
                            className: 'btn-sm btn-danger',
                            title: 'Order List',
                            pageSize: 'A2',
                            header: true,
                            footer: true,
                            orientation: 'landscape',
                            exportOptions: {
                                columns: ['0,1,2,3'],
                                stripHtml: false
                            }
                        }
                    ],
            });
        }
        $('#search').on('click',function(event){
			event.preventDefault();
            var starting_date = $("#starting_date").val();
            var ending_date = $("#ending_date").val();
            var order_status = $("#order_status").val();
            var order_id = $("#order_id").val();

            if(starting_date == '' && ending_date == '' && order_status == '' && order_id == ''){
                swal({
                title: 'Error!!!',
                text: "Enter Any Value",
                dangerMode: true,
                });
               return false;
            }else if(starting_date != '' && ending_date == ''){
                swal({
                    title: 'Error!!!',
                    text: "Enter End Date",
                    dangerMode: true,
                });
                $("#starting_date").val('');
                return false;
            }

			var table =  $('#data_table').DataTable({
				order: [],
				lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
				processing: true,
				serverSide: true,
				"bDestroy": true,
				ajax: {
					url: "{{ route('order-status-report') }}",
					type: "GET",
					data:{
						'starting_date':starting_date,
						'ending_date':ending_date,
						'order_status':order_status,
						'order_id':order_id,
					},
				},
				columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false},
                {data: 'orderNumber', name: 'orderNumber'},
                {data: 'courier', name: 'courier'},
                {data: 'officeStaff', name: 'officeStaff'},
                {data: 'customer', name: 'customer'},
                {data: 'product', name: 'product'},
                {data: 'amount', name: 'amount'},
                {data: 'status', name: 'status'},
                {data: 'action', searchable: false, orderable: false}
             ],
             dom: "<'row'<'col-sm-2'l><'col-sm-7 text-center'B><'col-sm-3'f>>tipr",
                    buttons: [
                            {
                                extend: 'colvis',
                                className: 'btn-sm btn-warning',
                                title: 'Order Report',
                                header: true,
                                footer: true,
                                exportOptions: {
                                    columns: ':visible',
                                }
                            },
                            {
                                extend: 'copy',
                                className: 'btn-sm btn-info',
                                title: 'Order Report',
                                header: true,
                                footer: true,
                                exportOptions: {
                                    columns: ':visible',
                                }
                            },
                            {
                                extend: 'csv',
                                className: 'btn-sm btn-success',
                                title: 'Order Report',
                                header: true,
                                footer: true,
                                exportOptions: {
                                    columns: ':visible',
                                }
                            },
                            {
                                extend: 'excel',
                                className: 'btn-sm btn-dark',
                                title: 'Order Report',
                                header: true,
                                footer: true,
                                exportOptions: {
                                    columns: ':visible',
                                }
                            },
                            {
                                extend: 'pdf',
                                className: 'btn-sm btn-primary',
                                title: 'Order Report',
                                pageSize: 'A2',
                                header: true,
                                footer: true,
                                exportOptions: {
                                    columns: ':visible',
                                }
                            },
                            {
                                extend: 'print',
                                className: 'btn-sm btn-danger',
                                title: 'Order Report',
                                pageSize: 'A2',
                                header: true,
                                footer: true,
                                orientation: 'landscape',
                                exportOptions: {
                                    columns: ':visible',
                                    stripHtml: false
                                }
                            },

                        ],
			});
		});

</script>
@endpush
@endsection
