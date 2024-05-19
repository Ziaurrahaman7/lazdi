@extends('admin.master')
@section('title', 'Add New Order')

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Add New Order</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Add New Order</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <form enctype="multipart/form-data" action="{{ route('order.store') }}" method="POST">
        @csrf
        <section class="content">
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-6">
                <div class="card card-primary">
                  <div class="card-header">
                    <h3 class="card-title">Customer Information</h3>
                  </div>
                  <div class="card-body">
                    <div class="form-group ">
                      <label for="billing_name">Name<span class="text-red">*</span></label>
                      <input required="" class="form-control @error('billing_name') is-invalid @enderror" placeholder="Customer Name" type="text" name="billing_name" value="{{ old('billing_name') }}">

                      @error('billing_name')
                        <span class="text-danger" role="alert">
                            <p>{{ $message }}</p>
                        </span>
                     @enderror
                    </div>

                    <div class="form-group ">
                      <label for="billing_mobile">Customer Phone<span class="text-red">*</span></label>
                      <input required="" type="text" placeholder="Customer Mobile" name="billing_mobile" class="form-control @error('billing_mobile') is-invalid @enderror" value="">
                      @error('billing_mobile')
                        <span class="text-danger" role="alert">
                            <p>{{ $message }}</p>
                        </span>
                     @enderror
                    </div>

                    <div class="form-group shipping-address-group ">
                      <label for="shipping_address1">Customer Address<span class="text-red">*</span></label>
                      <textarea required="" class="form-control @error('shipping_address1') is-invalid @enderror" rows="2" name="shipping_address_1" id="shipping_address1" placeholder="Customer Address" required></textarea>
                      @error('shipping_address1')
                        <span class="text-danger" role="alert">
                            <p>{{ $message }}</p>
                        </span>
                     @enderror
                    </div>

                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card card-primary">
                  <div class="card-header">
                    <h3 class="card-title">Action </h3>
                  </div>
                  <div class="card-body">
                    <div class="form-group" id="order_area">
                      <label>
                        <input type="radio" name="order_area" value="1" checked=""> Inside Dhaka </label>
                      <label>
                        <input type="radio" name="order_area" value="2"> Outside Dhaka </label>
                        @error('billing_name')
                        <span class="text-danger" role="alert">
                            <p>{{ $message }}</p>
                        </span>
                     @enderror
                    </div>

                    <div class="form-group">
                      <label>Courier </label>
                      <!--{{-- <select name="courier_service" id="courier_service" class="form-control select2">-->
                      <!--  <option>Select Courier</option>-->
                      <!--  <option value="1">sundarban courier service</option>-->
                      <!--  <option value="2">karatoa courier service</option>-->
                      <!--  <option value="3">S A paribahan courier service </option>-->
                      <!--  <option value="4">Janani courier service</option>-->
                      <!--  <option value="5">Redx</option>-->
                      <!--  <option value="6">Pathao</option>-->
                      <!--</select> --}}-->
                      <select name="courier_service" id="courier_service" class="form-control select2" required>
                        <option value="">Select Courier</option>
                        @foreach ($couriers as $courier)
                           <option value="{{ $courier->id }}">{{ $courier->name }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="form-group">
                      <label>Area Name</label>
                      <select name="area_id" id="area_id" class="form-control select2" >
                        <option value="" data-select2-id="select2-data-4-hfbh">Select Area</option>
                        <option value="1">Mohammadpur(Dhaka)</option>
                        <option value="2">Dhanmondi - Rd 3</option>
                        <option value="3">Gulshan 1 rd 1</option>
                        <option value="5">Kallyanpur</option>
                        <option value="6">Shyamoli(Dhaka)</option>
                        <option value="7">Adabor(Mohammadpur)</option>
                        <option value="8">Darussalam</option>
                        <option value="9">Gabtoli</option>
                        <option value="10">Pallabi</option>
                      </select>

                    </div>

                    <div class="form-group ">
                      <label for="product_weight">Product Weight<span class="text-red">*</span></label>
                      <input required="" type="number" placeholder="Product Weight" name="product_weight" class="form-control @error('product_weight') is-invalid @enderror" value="">
                      @error('product_weight')
                      <span class="text-danger" role="alert">
                          <p>{{ $message }}</p>
                      </span>
                   @enderror
                    </div>

                    <div class="form-group ">
                      <label for="weight">Order Number<span class="text-red">*</span></label>
                      <input type="text" placeholder="Order Number" name="order_number" class="form-control @error('order_number') is-invalid @enderror" value="">
                      @error('order_number')
                        <span class="text-danger" role="alert">
                            <p>{{ $message }}</p>
                        </span>
                     @enderror
                    </div>
                    <div class="form-group">
                      <label>Order Status<span class="text-red">*</span></label>
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
                      @error('order_status')
                        <span class="text-danger" role="alert">
                            <p>{{ $message }}</p>
                        </span>
                     @enderror
                    </div>

                    <div class="form-group">
                      <label> Order Note</label>
                      <textarea rows="2" class="form-control" name="order_note"></textarea>
                    </div>

                    <div class="form-group ">
                      <label>Shipping Date<span class="text-red">*</span></label>
                      <div class="input-group date">
                        <div class="input-group-addon"></div>
                        <input type="date" name="shiping_date" class="form-control pull-right @error('shiping_date') is-invalid @enderror" id="datepicker" value="{{date('Y-m-d')}}">

                        @error('shiping_date')
                        <span class="text-danger" role="alert">
                            <p>{{ $message }}</p>
                        </span>
                        @enderror
                      </div>

                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="card card-info">
                <div class="card-header">
                  <h3 class="card-title">Order Information</h3>
                </div>
                <div class="card-body">
                  <div class="card">
                    <div class="card-header">
                      <h3 class="card-title">Product Information</h3>
                    </div>
                    <div class="card-body p-0">
                      <div class="form-group">
                        <select  required="" name="product_id[]" id="product_ids" class="form-control select2 select2-hidden-accessible" multiple="" data-placeholder="Type... product name here..." style="width:100%;" data-select2-id="select2-data-product_ids" tabindex="-1" aria-hidden="true">
                          <option value="">Select Product</option>
                          @foreach ($products as $product)
                            <option value="{{ $product->id}}">{{ $product->title }}</option>
                          @endforeach
                        </select>
                      </div>

                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                              <tr>
                                <th class="name" width="30%">Product</th>
                                <th class="name" width="5%">Code</th>
                                <th class="image text-center" width="5%">Image</th>
                                <th class="quantity text-center" width="10%">Qty</th>
                                <th class="price text-center" width="10%">Price</th>
                                <th class="total text-right" width="10%">Sub-Total</th>
                              </tr>
                            </thead>
                            <tbody id="product_html">

                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5" class="text-right"> Sub Total</td>
                                    <td class="text-right"><span id="subtotal_price_total"></span>
                                    <input type="hidden" name="price_subtotal" class="form-control" id="subtotal_price" value="0">

                                </td>
                                </tr>
                                <tr>
                                    <td class="text-right" colspan="5"> <span class="extra bold">Delivery Cost</span></td>
                                    <td class="text-right"><input type="text" name="price_shipping" class="form-control" id="shipping_charge" value="0"></td>
                                </tr>
                                <tr>
                                    <td class="text-right" colspan="5"><span class="extra bold">Discount Price</span></td>
                                    <td class="text-right"><input type="text" name="discount_price" class="form-control" id="discount_price" value="0"></td>
                                </tr>
                                <tr>
                                    <td class="text-right" colspan="5"> <span class="extra bold">Advance Price</span></td>
                                    <td class="text-right"><input type="text" name="advance_price" class="form-control" id="advance_price" value="0"></td>
                                </tr>
                                <tr>
                                    <td class="text-right" colspan="5"> <span class="extra bold totalamout">Total</span></td> <td class="text-right"> <span class="bold totalamout"><p> <span id="total_cost"></span></p></span>
                                    <input type="hidden" name="price_total" id="order_total" value="">
                                </td></tr>
                            </tfoot>
                        </table>

                      <div class="card-footer">
                        <button type="submit" class="btn btn-primary" style="float:right">Submit</button>
                      </div>
                    </div>
                    <!-- /.card-body -->
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </form>
  </div>

@push('js')
<script>

$("#product_ids").on('change', function(e){
    e.preventDefault();
    var product_ids = $(this).val(); // Retrieve an array of selected IDs

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        }
    });

    // Clear the product HTML container
    $('#product_html').html('');

    // Loop through each selected product ID
    product_ids.forEach(function(product_id) {
        $.ajax({
            url: "{{ route('product-info') }}",
            type: "POST",
            data: {
                id: product_id,
            },
            success: function(result){
                $('#product_html').append(result.html);
                calculateSubtotalsAndTotal();
            },
        });
    });
});
</script>

<script>

$(document).on('input', '#product_html input[name="product_quantity[]"], #shipping_charge, #discount_price, #advance_price', function() {
    calculateSubtotalsAndTotal();
});

function calculateSubtotalsAndTotal() {
    var subTotal = 0;

    $('#product_html tr').each(function() {
        var qty = $(this).find('input[name="product_quantity[]"]').val();
        var price = $(this).find('.price').text();
        var subtotal = qty * price;

        $(this).find('.subtotal').text(subtotal);
        $(this).find('.product_total').val(subtotal);
        subTotal += subtotal;
    });

    var shippingCharge = parseFloat($('#shipping_charge').val()) || 0;
    var discountPrice = parseFloat($('#discount_price').val()) || 0;
    var advancePrice = parseFloat($('#advance_price').val()) || 0;

    var total = subTotal + shippingCharge - discountPrice - advancePrice;

    $('#subtotal_price_total').text(subTotal);
    $('#subtotal_price').val(subTotal);
    $('#total_cost').text(total);
    $('#order_total').val(total);
    $('#product_total_price').append(subtotal);
}


</script>
@endpush
@endsection
