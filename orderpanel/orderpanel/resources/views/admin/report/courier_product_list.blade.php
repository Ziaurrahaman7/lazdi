@extends('admin.master')
@section('title', 'Courier Product List')
@section('content')
@push('css')
<link rel="stylesheet" href="{{ asset('admin/DataTables/datatables.min.css') }}">
@endpush
<div class="content-wrapper" style="min-height: 105px;">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Courier Product List</h1>
          </div>
          <!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">
                <a href="#" style="color:black">Home</a>
              </li>
              <li class="breadcrumb-item active"> Courier Product List</li>
            </ol>
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </div>


    <section class="content">
		<div class="container-fluid">

			<div class="card">
				<div class="card-body">
					<table id="data_table" class="table table-bordered table-striped data-table table-hover">
                        <thead>
                            <tr role="row">
                                <th>SN</th>
                                <th>Order</th>
                                <th>Customer</th>
                                <th>Products</th>
                                <th>Courier</th>
                                <th>Booking Date</th>
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



@push('js')
<script src="{{ asset('admin/DataTables/datatables.min.js') }}"></script>
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
            url: "{{route('Courier.Booked.List')}}",
            type: "get"
        },

        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false},
            {data: 'order', name: 'order'},
            {data: 'customer', name: 'customer'},
            {data: 'product', name: 'product'},
            {data: 'courier', name: 'courier'},
            {data: 'booking', name: 'booking'},
            {data: 'action', searchable: false, orderable: false}
        ],
        dom: "<'row'<'col-sm-2'l><'col-sm-7 text-center'B><'col-sm-3'f>>tipr",
                buttons: [
                        {
                            extend: 'copy',
                            className: 'btn-sm btn-info',
                            title: 'Product List',
                            header: true,
                            footer: true,
                            exportOptions: {
                                columns: ['0,1,2,3,4,5'],
                            }
                        },
                        {
                            extend: 'csv',
                            className: 'btn-sm btn-success',
                            title: 'Product List',
                            header: true,
                            footer: true,
                            exportOptions: {
                                columns: ['0,1,2,3,4,5'],
                            }
                        },
                        {
                            extend: 'excel',
                            className: 'btn-sm btn-dark',
                            title: 'Product List',
                            header: true,
                            footer: true,
                            exportOptions: {
                                columns: ['0,1,2,3,4,5'],
                            }
                        },
                        {
                            extend: 'pdf',
                            className: 'btn-sm btn-primary',
                            title: 'Product List',
                            pageSize: 'A2',
                            header: true,
                            footer: true,
                            exportOptions: {
                                columns: ['0,1,2,3,4,5'],
                            }
                        },
                        {
                            extend: 'print',
                            className: 'btn-sm btn-danger',
                            title: 'Product List',
                            pageSize: 'A2',
                            header: true,
                            footer: true,
                            orientation: 'landscape',
                            exportOptions: {
                                columns: ['0,1,2,3,4,5'],
                                stripHtml: false
                            }
                        }
                    ],

                    initComplete: function () {
                        // Display an alert when DataTable is done loading
                        $('.check-invoice').click(function () {
                            var cinvoice = $(this).data('cinvoice');
                            checkInvoiceStatus(cinvoice);
                            console.log(cinvoice);
                        });

                        $('.check-consignment').click(function () {
                            var consignment = $(this).data('consignment');
                            checkConsignmentId(consignment);
                            console.log(consignment);
                        });

                        $('.check-tracking').click(function () {
                            var tracking = $(this).data('tracking');
                            checkTrackingId(tracking);
                            console.log(tracking);
                        });

                        function checkInvoiceStatus(invoice) {
                            $.ajax({
                                url: "{{ url('courier-invoice-check') }}" + '/' + invoice,
                                type: 'GET',
                                success: function (response) {
                                    // Handle the response here, e.g., show it in a modal or alert
                                    alert(response);
                                },
                                error: function (xhr) {
                                    // Handle errors if any
                                    console.log(xhr);
                                    alert('Error occurred while checking invoice status.');
                                }
                            });
                        }

                        function checkConsignmentId(consignment) {
                            $.ajax({
                                url: "{{ url('courier-consignment-check') }}" + '/' + consignment,
                                type: 'GET',
                                success: function (response) {
                                    // Handle the response here, e.g., show it in a modal or alert
                                    alert(response);
                                },
                                error: function (xhr) {
                                    // Handle errors if any
                                    console.log(xhr);
                                    alert('Error occurred while checking consignment ID.');
                                }
                            });
                        }

                        function checkTrackingId(tracking) {
                            $.ajax({
                                url: "{{ url('courier-tracking-check') }}" + '/' + tracking,
                                type: 'GET',
                                success: function (response) {
                                    // Handle the response here, e.g., show it in a modal or alert
                                    alert(response);
                                },
                                error: function (xhr) {
                                    // Handle errors if any
                                    console.log(xhr);
                                    alert('Error occurred while checking tracking ID.');
                                }
                            });
                        }

                    }
        });
    });


</script>
@endpush
@endsection
