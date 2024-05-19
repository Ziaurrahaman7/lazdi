@extends('admin.master')
@section('title', 'Order List')
@section('content')
@push('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />
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
    width: 19%;
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
<div class="content-wrapper" style="min-height: 105px;">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Orders</h1>
          </div>
          <!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">
                <a href="#" style="color:black">Home</a>
              </li>
              <li class="breadcrumb-item active"> Orders List</li>
            </ol>
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </div>

    <section class="content">
      <div class="row" style="cursor: pointer;">
        <span id="order_status_view">
          <div class="row" style="cursor: pointer;">
            <div class="col-12 col-lg-12 col-xl-12">
              <button onclick="orderStatus(1)" type="button" class="btn btn-primary order_status  "> New <span class="badge badge-light">{{ $new ?? '0' }}</span>
              </button>
              <button onclick="orderStatus(2)" type="button" class="btn btn-primary order_status "> Pending For Pyment <span class="badge badge-light"> {{ $pendingPayment ?? '0' }}</span>
              </button>
              <button onclick="orderStatus(3)" type="button" class="btn btn-primary order_status "> Pending <span class="badge badge-light"> {{ $pending ?? '0' }}</span>
              </button>
              <button onclick="orderStatus(4)" type="button" class="btn btn-primary order_status "> Courier <span class="badge badge-light"> {{ $courier ?? '0' }}</span>
              </button>
              <button onclick="orderStatus(5)" type="button" class="btn btn-primary order_status "> Invoice <span class="badge badge-light"> {{ $invoice ?? '0' }}</span>
              </button>
              <button onclick="orderStatus(6)" type="button" class="btn btn-primary order_status "> Pending Invoice <span class="badge badge-light"> {{ $pendingInvoice ?? '0' }}</span>
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
        </span>
      </div>

      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-12 col-md-4">
              <h3 class="card-title">Order List</h3>

              <button type="button" data-toggle="modal" data-target="#exchangedata" class="btn btn-danger btn-sm" style="float:right" id="deleteAll">
                <i class="fa fa-exchange"></i> Exchange Order </button>
            </div>
            <div class="col-12 col-md-4">
              <input type="text" id="order_id" placeholder="Enter Order ID " class="form-control exchange">
            </div>
            <div class="col-12 col-md-4">
              <input type="text" id="phone" placeholder="Enter Phone Number" class="form-control exchange">
            </div>
          </div>
        </div>

    </section>

    <section class="content">
		<div class="container-fluid">
			<div class="card">
				<div class="card-body">
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
				</div>
			</div>
		</div>
	</section>

</div>

<div class="modal fade" id="exchangedata" tabindex="-1" role="dialog" aria-labelledby="demoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="demoModalLabel">{{ __('Order Exchange')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="edit-data" action="#">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="title" class="col-sm-12 col-form-label">Exchange To<span class="text-red">*</span></label>
                        <div class="col-sm-12">
                            <select id="parentIdEdit" class="form-control" >
                                <option value="">--Select--</option>
                                @foreach ($users as $key => $user)
                                    <option value="{{ $user->id }}" > {{ $user->username }} </option>
                                @endforeach
                            </select>

                            <input type="hidden" id="editId">
                            @error('title')
                            <span class="text-danger" role="alert">
                                <p>{{ $message }}</p>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="category_id" class="col-sm-12 col-form-label"> Order Status</label>
                        <div class="col-sm-12">
                            <select name="order_status" id="order_status" class="form-control @error('order_status') is-invalid @enderror" required>
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
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">{{ __('Close')}}</button>
                <button type="button" id="update" class="btn btn-primary">{{ __('Exchange Now')}}</button>
            </div>
        </div>
    </div>
</div>

@push('js')
<script src="{{ asset('admin/DataTables/datatables.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>
<script>
    $(document).ready( function () {
    var dTable = $('#data_table').DataTable({
        order: [],
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        processing: true,
        responsive: false,
        serverSide: true,
        scroller: {
            loadingIndicator: false
        },
        language: {
              processing: '<i class="ace-icon fa fa-spinner fa-spin orange bigger-500" style="font-size:60px;text-align:center;"></i>'
            },
        pagingType: "full_numbers",
        ajax: {
            url: "{{route('order.index')}}",
            type: "get"
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
    });


    $('#data_table').on('click', '.btn-delete[data-remote]', function (e) {
            e.preventDefault();

            const url = $(this).data('remote');
            swal({
                    title: `Are you sure?`,
                    text: "Want to delete this record?",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'json',
                    data: {submit: true, _method: 'delete', _token: "{{ csrf_token() }}"}
                }).always(function (data) {
                    $('#data_table').DataTable().ajax.reload();
                    if (data.success === true) {
                        toastr.success(data.message, { positionClass: 'toast-bottom-full-width', });
                    }else{
                        toastr.error(data.message, { positionClass: 'toast-bottom-full-width', });
                    }
                });
            }
            });
        });


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

        $("#order_id, #phone").on("input", function() {
            var order_id = $('#order_id').val();
            var phone = $('#phone').val();

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
                url: "{{route('order-wise-product')}}",
                type: "POST",
                data:{
                    order_id:order_id,
                    phone:phone,
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
});

//  toastr.options.timeOut = 300;
@if(Session::has('success'))
    toastr.options =
    {
        "closeButton" : true,
        "progressBar" : true,
        "timeOut" : 2000
    };

    toastr.success("{{ session('success') }}");
  @endif

 @if(Session::has('error'))
    toastr.options =
    {
        "closeButton" : true,
        "progressBar" : true
    };
    toastr.error("{{ session('error') }}");
 @endif

</script>
@endpush
@endsection
