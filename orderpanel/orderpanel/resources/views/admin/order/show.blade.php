@extends('admin.master') @section('title', 'Order List') @section('content') <div class="content-wrapper" style="min-height: 105px;">
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
    </style>
    <section class="content">
      <div class="row" style="cursor: pointer;">
        <span id="order_status_view">
          <style>
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
          <div class="row" style="cursor: pointer;">
            <div class="col-12 col-lg-12 col-xl-12">
              <button onclick="orderStatus('new')" type="button" class="btn btn-primary order_status  "> New <span class="badge badge-light">12</span>
              </button>
              <button onclick="orderStatus('pending')" type="button" class="btn btn-primary order_status "> Pending <span class="badge badge-light"> 1</span>
              </button>
              <button onclick="orderStatus('pending_payment')" type="button" class="btn btn-primary order_status "> Pending Pyment <span class="badge badge-light"> 0</span>
              </button>
              <button onclick="orderStatus('on_courier')" type="button" class="btn btn-primary order_status "> Courier <span class="badge badge-light"> 0</span>
              </button>
              <button onclick="orderStatus('invoice')" type="button" class="btn btn-primary order_status "> Invoice <span class="badge badge-light"> 3226</span>
              </button>
              <button onclick="orderStatus('ready_to_deliver')" type="button" class="btn btn-primary order_status "> Pending Invoice <span class="badge badge-light"> 0</span>
              </button>
              <button onclick="orderStatus('delivered')" type="button" class="btn btn-primary order_status "> Delivered <span class="badge badge-light"> 31521</span>
              </button>
              <button onclick="orderStatus('booking')" type="button" class="btn btn-primary order_status "> Booking <span class="badge badge-light"> 6185</span>
              </button>
              <button onclick="orderStatus('return')" type="button" class="btn btn-primary order_status "> Return <span class="badge badge-light"> 734</span>
              </button>
              <button onclick="orderStatus('cancled')" type="button" class="btn btn-primary order_status "> Cancled <span class="badge badge-light"> 26203</span>
              </button>
            </div>
          </div>
        </span>
        <div class="card">
          <div class="card-header">
            <div class="row">
              <div class="col-12 col-md-4">
                <h3 class="card-title">Order List</h3>
                <button type="button" data-toggle="modal" data-target="#modal-default" class="btn btn-danger btn-sm" style="float:right" id="deleteAll">
                  <i class="fas fa-exchange"></i> Exchange Order </button>
              </div>
              <div class="col-12 col-md-4">
                <input type="text" id="order_id" placeholder="Enter Order ID " class="form-control">
              </div>
              <div class="col-12 col-md-4">
                <input type="text" id="pagination_search_by_phone" placeholder="Enter Phone Number" class="form-control">
              </div>
            </div>
          </div>
          <div class="card-body p-0 table-responsive">
            <table class="table table-bordered projects">
              <thead>
                <tr style="text-align:center">
                  <th width="10%"> Order ID </th>
                  <th>Courier Information</th>
                  <th style="width: 9%;">
                    <span style="font-size: 15px;"> Office Staff</span>
                    <br>
                    <input type="checkbox" name="all_select" id="checkAll">
                  </th>
                  <th style="width:15%;text-align:left">Customer</th>
                  <th style="width:15%;text-align:left">Products</th>
                  <th> Amount</th>
                  <th> Status</th>
                  <th width="10%">Action </th>
                </tr>
              </thead>
              <tbody>
                <tr>
                @foreach($data as $item)

                  <td>
                    <span class="badge badge-pill badge-danger" style="font-size:18px"> {{ $item->order_number}}</span>
                    <span class="badge badge-pill badge-success" style="font-size:18px"> {{ Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}</span>{{ date('g:i:a', strtotime($item->created_at)) }} <br>
                    <span style="color:green"> Shipping Date {{ date('d-m-Y', strtotime($item->created_at)) }} </span>
                  </td>

                  <td>
                    <span style="color:red;font-size: 17px;font-weight: bold"></span>
                    <br>
                    <br>
                    <span style="color:red;font-size: 15px;font-weight: bold"> Weight : {{$item->product_weight}}</span>
                    <br>
                    <span style="color:black;font-size: 15px;font-weight: bold"> Invoice : {{$item->product_weight}}</span>
                  </td>

                  <td style="text-align: center">
                    <input style="width: 15px;text-align: center" type="checkbox" value="73876" class="checkAll ">
                    <span data-toggle="modal" data-target="#modal-edit" onclick="orderEdit(73876)" class="badge badge-pill badge-primary"> {{$item->product_weight}} </span>
                    <span class="badge badge-pill badge-danger"> Inside Dhaka {{$item->product_weight}}</span>
                    <span class="badge badge-pill badge-danger"></span>
                  </td>

                  <td>
                    <span class="badge badge-pill badge-info" style="font-size:18px"> {{$item->product_weight}}মোহাম্মদ ফিরোজ</span>
                    <br>
                    <span class="badge badge-pill badge-success" style="font-size:18px"> {{$item->product_weight}}01887010560</span>
                    <br> {{$item->address ?? '-'}}<br>
                    <span style="color:red;font-weight: 400">Note: {{$item->product_weight}}</span>
                  </td>

                  <td>
                    <span class="product-title">Premium Quality Aluminum Juicer Squeezer{{$item->product_weight}}</span>
                    <img class="img-responsive" width="50" src="#">
                    <p> 980.00 <i class="fal fa-times"></i> 1= 980.00 {{$item->product_weight}}</p>
                    <p style="color:red;font-weight:bold;position: absolute;margin-top: 8px;">Code :{{$item->product_weight}}</p>
                    <br>
                  </td>

                  <td> 1,060.00 {{$item->product_weight}}</td>

                  <td>
                    <span class="badge badge-pill badge-info">new{{$item->product_weight}}</span>
                    <br>
                  </td>

                  <td>
                    <a title="edit" href="{{route('order.edit', $item->id)}}" class=" btn btn-success btn-sm">
                      <i class="fa fa-pencil"></i>
                    </a>
                  </td>

                @endforeach

                </tr>
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
      </div>
      <!-- /.card -->
    </section>
    <div class="modal fade" id="modal-default" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Order Exchange</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label>Exchange to</label>
              <select name="staff_id" id="staff_id" class="form-control">
                <option value="">----select----</option>
                <option value="43">Nur Islam </option>
                <option value="94">Office Staff 2 </option>
                <option value="96">Anik </option>
                <option value="97">office staff 3 sumi </option>
                <option value="99">Office Staff 4</option>
              </select>
            </div>
            <div class="form-group">
              <label>Order Status</label>
              <select name="order_status_convert" id="order_status_convert" class="form-control">
                <option value="">----select----</option>
                <option value="new">New</option>
                <option value="pending_payment">Pending for Payment</option>
                <option value="pending">Pending</option>
                <option value="on_courier"> Courier</option>
                <option value="delivered">Delivered</option>
                <option value="cancled">Cancelled</option>
                <option value="ready_to_deliver">Pending Invoice</option>
                <option value="invoice">Invoice</option>
                <option value="booking">Booking</option>
              </select>
            </div>
          </div>
          <div class="modal-footer text-right">
            <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="exchange_now">Exchange Now</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <div class="modal fade show" id="modal-edit" aria-modal="true" role="dialog">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Order Edit History</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body" id="order_edit_history"></div>
          <div class="modal-footer text-right">
            <button type="button" class="btn   btn-danger btn-sm" data-dismiss="modal">Close</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <input type="hidden" name="hidden_page" id="hidden_page" value="1">
    <input type="hidden" name="status" id="status" value="new">
    <script>
      window.load = order_status()

      function orderEdit(order_id) {
        $.ajax({
          type: "GET",
          url: "https://www.dhakabaazar.com/adminpanel/admin/order/editHistory/" + order_id,
          success: function(data) {
            $('#order_edit_history').html(data);
          }
        })
      }
      $("#exchange_now").click(function() {
        var staff_id = $("#staff_id").val();
        var order_status_convert = $("#order_status_convert").val();
        // if (staff_id == '') {
        //     alert("Please Select at least One Staff")
        //     return false;
        // }
        var order_id = new Array();
        //var allId=$('.checkAll').val();
        $('.checkAll').each(function() {
          if ($(this).is(":checked")) {
            order_id.push(this.value);
          }
        });
        if (order_id.length > 0) {
          $.ajax({
            url: 'https://www.dhakabaazar.com/adminpanel/admin/orderExchange',
            data: {
              order_id: order_id,
              order_status: order_status_convert,
              staff_id: $("#staff_id").val(),
              "_token": "XX6NOmcY3qDeL2llFpEqC4rh50qDeJgM5qW8sizc"
            },
            type: 'post',
            success: function(data) {
              location.reload();
            }
          });
        } else {
          alert("Please select Order Id")
        }
      });
      //$('#checkAll').change(function () {
      $(document).on("change", "#checkAll", function(event) {
        if ($(this).is(":checked")) {
          $('.checkAll').prop('checked', true);
        } else if ($(this).is(":not(:checked)")) {
          $('.checkAll').prop('checked', false);
        }
      });
      $('#deleteAll').click(function(e) {
        e.preventDefault();
        var order_id = new Array();
        //var allId=$('.checkAll').val();
        $('.checkAll').each(function() {
          if ($(this).is(":checked")) {
            order_id.push(this.value);
          }
        });
        if (order_id.length == 0) {
          Swal.fire({
            position: 'top-end',
            icon: 'error',
            title: 'Please Select At least One Order Id',
            showConfirmButton: true,
            timer: 2000
          })
        }
      });

      function fetch_data(page, status) {
        $.ajax({
          type: "GET",
          url: "https://www.dhakabaazar.com/adminpanel/admin/order/pagination?page=" + page + "&status=" + status,
          success: function(data) {
            $('tbody').html('');
            $('tbody').html(data);
          }
        })
      }

      function order_status() {
        $.ajax({
          type: "GET",
          url: "https://www.dhakabaazar.com/adminpanel/admin/order/order_status",
          success: function(data) {
            $('#order_status_view').html(data);
          }
        })
      }

      function orderStatus(status) {
        $('#status').val(status);
        let page = 1;
        fetch_data(page, status);
      }

      function pagination_search_by_order_id(query) {
        var page = 1
        $.ajax({
          type: "GET",
          url: "https://www.dhakabaazar.com/adminpanel/admin/order/pagination_search_by_order_id?page=" + page + "&query=" + query,
          success: function(data) {
            $('tbody').html('');
            $('tbody').html(data);
          }
        })
      }

      function pagination_search_by_phone(query) {
        var page = 1
        $.ajax({
          type: "GET",
          url: "https://www.dhakabaazar.com/adminpanel/admin/order/pagination_search_by_phone?page=" + page + "&query=" + query,
          success: function(data) {
            $('tbody').html('');
            $('tbody').html(data);
          }
        })
      }

      function pagination_search_by_product_code(query) {
        var page = 1
        $.ajax({
          type: "GET",
          url: "https://www.dhakabaazar.com/adminpanel/order/pagination_search_by_product_code?page=" + page + "&query=" + query,
          success: function(data) {
            $('tbody').html('');
            $('tbody').html(data);
          }
        })
      }
      $(document).on('keyup input', '#order_id', function() {
        var query = $('#order_id').val();
        if (query.length > 1) {
          pagination_search_by_order_id(query);
        } else {
          fetch_data(1, 'new');
        }
      });
      $(document).on('keyup input', '#product_code', function() {
        var query = $('#product_code').val();
        var page = $('#hidden_page').val();
        var status = $('#status').val();
        if (query.length > 3) {
          pagination_search_by_product_code(page, query);
        } else {
          fetch_data(1, 'new');
        }
      });
      $(document).on('keyup input', '#pagination_search_by_phone', function() {
        var query = $('#pagination_search_by_phone').val();
        if (query.length > 7) {
          pagination_search_by_phone(query);
        } else {
          fetch_data(1, 'new');
        }
      });
      $(document).on('click', '.pagination a', function(event) {
        event.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        $('#hidden_page').val(page);
        var status = $('#status').val();
        fetch_data(page, status);
      });
      $(document).on('click', '.status_check', function() {
        var status = $(this).val()
        $('#status').val(status);
        var status = $('#status').val();
        var page = 1;
        fetch_data(page, status);
      });
    </script>
  </div> @endsection
