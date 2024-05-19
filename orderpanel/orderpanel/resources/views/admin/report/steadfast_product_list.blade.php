@extends('admin.master')
@section('title', 'Stead Fast Product List')
@section('content')
@push('css')
<link rel="stylesheet" href="{{ asset('admin/DataTables/datatables.min.css') }}">
@endpush
<div class="content-wrapper" style="min-height: 105px;">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Stead Fast Product List</h1>
                </div>
                <!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="#" style="color:black">Home</a>
                        </li>
                        <li class="breadcrumb-item active"> Stead Fast Product List</li>
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
            <button id="sendSelectedRows" class="btn btn-primary">Booking Selected Products</button>
            <div class="card">
                <div class="card-body">
                    <table id="data_table" class="table table-bordered table-striped data-table table-hover">
                        <thead>
                            <tr role="row">
                                <th><input type="checkbox" id="checkAll"></th>
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



@push('js')
<script src="{{ asset('admin/DataTables/datatables.min.js') }}"></script>
<script>
     var selectedRowIds = []; // Array to store selected row IDs
     var checkAllCheckbox = $('#checkAll');
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
            url: "{{route('steadfast.product.list')}}",
            type: "get"
        },

        columns: [
            { data: 'All', name: 'All', searchable: false, orderable: false }, // Custom column with checkboxes
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
                    initComplete: function () {
                // Handle checkbox changes using event delegation
                $('#data_table').on('change', '.checkbox-row', function () {
                    updateSelectedRowIds(); // Update selectedRowIds array
                });

                // Check/uncheck all checkboxes when the "checkAll" checkbox is clicked
                $('#data_table').on('change', '#checkAll', function () {
                    $('.checkbox-row').prop('checked', $(this).is(':checked'));
                    updateSelectedRowIds(); // Update selectedRowIds array
                });

                $('#sendSelectedRows').on('click', function () {
                    console.log(selectedRowIds);
                    $.ajax({
                        type: 'POST',
                        url: `{{ url('/courier-booked') }}`,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {'idArray': selectedRowIds},
                        success: function (response) {
                            // Handle the response if needed
                            alert(response);
                            location.reload(true);
                        },
                        error: function (error) {
                            // Handle errors if the request fails
                            console.log(error)
                        }
                    });
                });
            }
        });

        // Function to update selected row IDs
        function updateSelectedRowIds() {
            selectedRowIds = [];
            $('.checkbox-row:checked').each(function () {
                selectedRowIds.push($(this).data('order-id'));
                console.log($(this).data('order-id'))
            });
        }

        // Event listener for DataTable's page event
        dTable.on('page.dt', function () {
            checkAllCheckbox.prop('checked', false); // Uncheck "checkAll" on page change
        });
    });
</script>
@endpush
@endsection
