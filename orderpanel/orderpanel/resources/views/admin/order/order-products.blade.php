@extends('admin.master')
@section('title', 'Order Product')

@section('content')
<div class="content-wrapper" style="min-height: 114px;">
    <div class="content-header">
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Orders</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="https://www.dhakabaazar.com/adminpanel" style="color:black">Home</a></li>
                <li class="breadcrumb-item active">  Orders List</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
</div>

<section class="content">
<div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">

                 <form action="{{route('order.report_by_date')}}" method="get">
              <div class="row">
                  <div class="col-md-3">
                      <h3 class="card-title">Order Product Report</h3>
                  </div>

                  <div class="col-md-3">
                      <div class="input-group input-group-sm">
                          <input type="date" name="order_date_start" value="{{ request('order_date_start') ??  date('Y-m-d') }}"  class="form-control float-right">

                      </div>
                  </div>
                  <div class="col-md-3">
                      <div class="input-group input-group-sm">
                          <input type="date" name="order_date_end" value="{{ request('order_date_end') ??  date('Y-m-d') }}"  class="form-control float-right">

                      </div>
                  </div>

                  <div class="col-md-3">
                      <div class="input-group input-group-sm">
                          <input type="text" name="product_code" value="{{ request('product_code') }}"  class="form-control float-right" placeholder="Search product code here ...">
                          <div class="input-group-append">
                              <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                          </div>
                      </div>
                  </div>
              </div>
                 </form>

            <div class="card-tools">

            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive p-0">
            <table class="table table-hover table-bordered">
              <thead>
                <tr>
                  <th>SL</th>
                  <th>Product Name</th>
                  <th style="text-align:center">Product Code</th>
                  <th style="text-align:center" width="5%">Quantity</th>
                </tr>
              </thead>
              <tbody>
                @php
                $totalSales = 0;
            @endphp
                @foreach($productQuantities as $title => $product)
                @php
                $totalSales += $product['quantity'];
            @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $title }}</td>
                    <td>{{ $product['sku'] }}</td>
                    <td>{{ $product['quantity'] }}</td>
                </tr>
            @endforeach
            <tr>
                <td style="text-align: right" colspan="3"><strong>Total Sales</strong></td>
                <td><strong>{{ $totalSales }}</strong></td>
            </tr>
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
    </div>

</section>
{{-- @endsection --}}
@endsection

