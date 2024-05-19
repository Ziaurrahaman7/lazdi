@extends('admin.master')
@section('title', 'Edit Order')

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Edit Order</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Edit Order</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <form enctype="multipart/form-data" action="{{ route('order.update', $order->id) }}" method="POST">
        @csrf
        @method('PUT')
        <section class="content">
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-4">
                <div class="card card-primary">
                  <div class="card-header">
                    <h3 class="card-title">Customer Information</h3>
                  </div>
                  <div class="card-body">
                    <div class="form-group ">
                      <label for="billing_name">Customer Name<span class="text-red">*</span> </label>
                      <input class="form-control @error('billing_name') is-invalid @enderror" placeholder="Customer Name" type="text" name="billing_name" value="{{ $customer->billing_first_name }}" required>
                      @error('billing_name')
                        <span class="text-danger" role="alert">
                            <p>{{ $message }}</p>
                        </span>
                     @enderror
                    </div>

                    <div class="form-group ">
                      <label for="billing_mobile">Customer Phone<span class="text-red">*</span></label>
                      <input required="" type="text" placeholder="Customer Mobile" name="billing_mobile" class="form-control @error('billing_mobile') is-invalid @enderror" value="{{ $customer->shipping_phone_number }}">
                      @error('billing_mobile')
                        <span class="text-danger" role="alert">
                            <p>{{ $message }}</p>
                        </span>
                     @enderror
                    </div>

                    <div class="form-group shipping-address-group ">
                      <label for="shipping_address1">Customer Address<span class="text-red">*</span> </label>
                      <textarea required="" class="form-control  @error('shipping_address1') is-invalid @enderror" rows="2" name="shipping_address_1" id="shipping_address1" placeholder="Customer Address" required>{{ $customer->shipping_address_1 }}</textarea>
                      @error('shipping_address1')
                      <span class="text-danger" role="alert">
                          <p>{{ $message }}</p>
                      </span>
                   @enderror
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-4">

                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Order Edit History</h3>
                    </div>

                    <div class="card-body">
                        <div style="height:300px;overflow-y:scroll">

                            <table class="table table-bordered">

                                <tr>
                                    <th>Staff Name</th>
                                    <th>Order Note</th>
                                </tr>

                                <tbody>

                                @foreach($note as $item)
                                    @php
                                        $users = DB::table('users')->where('id', $item->created_by)->get();
                                    @endphp
                                    @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->username }}</td>
                                        <td>{{ $item->note ?? '-'}} ({{ date('d-m-Y', strtotime($item->created_at)).', '.date('g:i a', strtotime($item->created_at)) }})</td>
                                    </tr>
                                    @endforeach
                                @endforeach

                            </tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </div>
              <div class="col-md-4">
                <div class="card card-primary">
                  <div class="card-header">
                    <h3 class="card-title">Action </h3>
                  </div>
                  <div class="card-body">
                    <div class="form-group" id="order_area">
                      <label>
                        <input type="radio" name="order_area" value="1" {{ $order->order_area == 1 ? 'checked'  : '' }} checked=""> Inside Dhaka </label>
                      <label>
                        <input type="radio" name="order_area" value="1" {{ $order->order_area == 2 ? 'checked'  : '' }}> Outside Dhaka </label>
                    </div>
                    <div class="form-group">
                      <label>Courier </label>

                      <select name="courier_service" id="courier_service" class="form-control select2" required>
                        <option value="">Select Courier</option>
                        @foreach ($couriers as $courier)
                           <option value="{{ $courier->id }}" {{ $order->courier_service == $courier->id ? 'selected' : '' }}>{{ $courier->name }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="form-group">
                      <label>Area Name</label>
                      <select name="area_id" id="area_id" class="form-control select2" >
                        <option value="" data-select2-id="select2-data-4-hfbh">Select Area</option>
                        <option value="1" {{ $order->area_id == 1 ? 'selected' : '' }}>Mohammadpur(Dhaka)</option>
                        <option value="2" {{ $order->area_id == 2 ? 'selected' : '' }}>Dhanmondi - Rd 3</option>
                      </select>

                    </div>
                    <div class="form-group ">
                      <label for="weight">Product Weight<span class="text-red">*</span></label>
                      <input required="" type="number" placeholder="Product Weight" name="product_weight" class="form-control @error('product_weight') is-invalid @enderror" value="{{ $order->product_weight }}">
                      @error('product_weight')
                      <span class="text-danger" role="alert">
                          <p>{{ $message }}</p>
                      </span>
                   @enderror
                    </div>

                    <div class="form-group ">
                      <label for="weight">Order Number<span class="text-red">*</span></label>
                      <input type="text" placeholder="Oder Number" name="order_number" class="form-control @error('order_number') is-invalid @enderror" value="{{ $order->order_number }}">
                      @error('order_number')
                      <span class="text-danger" role="alert">
                          <p>{{ $message }}</p>
                      </span>
                    @enderror
                    </div>

                    <div class="form-group">
                      <label>Order Status<span class="text-red">*</span></label>
                      <select name="order_status" id="order_status" class="form-control  @error('order_status') is-invalid @enderror" required>
                        <option value="1" {{ $order->status == 1 ? 'selected' : '' }}>New</option>
                        <option value="2" {{ $order->status == 2 ? 'selected' : '' }}>Pending for Payment</option>
                        <option value="3" {{ $order->status == 3 ? 'selected' : '' }}>Pending</option>
                        <option value="4" {{ $order->status == 4 ? 'selected' : '' }}>Courier</option>
                        <option value="5" {{ $order->status == 5 ? 'selected' : '' }}>Invoice</option>
                        <option value="6" {{ $order->status == 6 ? 'selected' : '' }}>Pending Invoice</option>
                        <option value="7" {{ $order->status == 7 ? 'selected' : '' }}>Delivered</option>
                        <option value="8" {{ $order->status == 8 ? 'selected' : '' }}>Booking</option>
                        <option value="9" {{ $order->status == 9 ? 'selected' : '' }}>Return</option>
                        <option value="10" {{ $order->status == 10 ? 'selected' : '' }}>Cancelled</option>
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
                        <input type="date" name="shiping_date" class="form-control pull-right @error('shiping_date') is-invalid @enderror" id="datepicker" value="{{ $order->shiping_date }}">
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
                                        <select name="product_id[]" id="product_ids"
                                            class="form-control select2 select2-hidden-accessible" multiple=""
                                            data-placeholder="Type... product name here..." style="width:100%;"
                                            data-select2-id="select2-data-product_ids" tabindex="-1" aria-hidden="true">
                                            <option value="">Select Product</option>
                                            @foreach ($products as $key => $product)
                                            @foreach ($orderProducts as $orderProduct)
                                            
                                            <option value="{{ $product->id}}">{{ $product->title }}
                                            </option>
                                            @endforeach
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
                                            @php
                                                $totalprice = 0;
                                                $subTotal = 0;
                                            @endphp
                                            @foreach ($products as $product)

                                            @foreach ($orderProducts as $key => $orderProduct)
                                            @if( $product->id == $orderProduct->product_id)
                                            <tr>
                                                <td>{{ $product->title }}</td>
                                                <td>{{ $product->sku }}</td>
                                                <td>{{ $product->image_default ?? ''}}</td>

                                                <td><input type="number" name="product_quantity[]" id="qty"
                                                        value="{{ $orderProduct->product_quantity}}"
                                                        class="form-control" placeholder="Enter qty"></td>

                                                <td class="price">{{ $orderProduct->product_unit_price/100 }}</td>
                                                @php

                                                    $totalprice = $orderProduct->product_quantity * $orderProduct->product_unit_price/100;
                                                    $subTotal += $totalprice;
                                                @endphp
                                                <td class="text-right">
                                                    <p class="subtotal">{{$totalprice }}</p>
                                                </td>
                                                <input type="hidden" name="product_id[]" class="form-control"
                                                value="{{ $orderProduct->product_id }}" readonly>
                                                <input type="hidden" name="product_code[]" class="form-control"
                                                    value="{{ $orderProduct->product_code }}" readonly>

                                                <input type="hidden" name="product_unit_price[]" class="form-control"
                                                    value="{{ $orderProduct->product_unit_price/100 }}" readonly>

                                                <input type="hidden" id="product_subtotal" name="product_total_price[]"
                                                    class="form-control" value="{{$totalprice}}">

                                            </tr>
                                            @endif
                                            @endforeach
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="5" class="text-right"> Sub Total</td>
                                                <td class="text-right"><span id="subtotal_price_total">{{
                                                         $subTotal }}</span>
                                                   
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-right" colspan="5"> <span class="extra bold">Delivery
                                                        Cost</span></td>
                                                <td class="text-right"><input type="text" name="price_shipping"
                                                        class="form-control" id="shipping_charge"
                                                        value="{{ $customer->delivery_cost}}"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right" colspan="5"><span class="extra bold">Discount
                                                        Price</span></td>
                                                <td class="text-right"><input type="text" name="discount_price"
                                                        class="form-control" id="discount_price"
                                                        value="{{ $order->customDiscount/100 }}"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right" colspan="5"> <span class="extra bold">Advance
                                                        Price</span></td>
                                                <td class="text-right"><input type="text" name="advance_price"
                                                        class="form-control" id="advance_price"
                                                        value="{{ $order->advance_price/100 }}"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right" colspan="5"> <span
                                                        class="extra bold totalamout">Total</span></td>
                                                <td class="text-right">
                                                    <input type="text" name="price_total" id="order_total"
                                                        value="{{ $order->price_total/100 }}" class="form-control"
                                                        readonly>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>

                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary"
                                            style="float:right">Update</button>
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
{{-- <script>

    var totalPrice = {{ $order->price_total }};

    // $('#total_cost').append(totalPrice);

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
</script> --}}
<script>
     $(document).ready(function() {
        // Fetch and display the selected product rows on page load
        var selectedProductIds = $("#product_ids").val();
        if (selectedProductIds) {
            fetchSelectedProducts(selectedProductIds);
        }

        // Handle the change event when selecting new products
        $("#product_ids").on('change', function(e) {
            e.preventDefault();
            var productIds = $(this).val(); // Retrieve an array of selected IDs
            if (productIds) {
                fetchSelectedProducts(productIds);
            } else {
                // Clear the table if no products are selected
                $('#product_html').empty();
                calculateSubtotalsAndTotal();
            }
        });

        function fetchSelectedProducts(productIds) {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                }
            });

            // Clear the product HTML container
            // $('#product_html').empty();

            // Loop through each selected product ID
            productIds.forEach(function(productId) {
                $('#product_html').empty();
                $.ajax({
                    url: "{{ route('product-info') }}",
                    type: "POST",
                    data: {
                        id: productId,
                    },
                    success: function(result) {
                        $('#product_html').append(result.html);
                        calculateSubtotalsAndTotal();
                    },
                });
            });
        }
    });
</script>
<script>

// $(document).on('input', '#product_html input[name="product_quantity[]"], #shipping_charge, #discount_price, #advance_price', function() {
//     calculateSubtotalsAndTotal();
// });
$(document).ready(function() {
    // Call the calculateSubtotalsAndTotal function on page load
    calculateSubtotalsAndTotal();

    // Bind the calculateSubtotalsAndTotal function to the specified input events
    $(document).on('input', '#product_html input[name="product_quantity[]"], #shipping_charge, #discount_price, #advance_price', function() {
        calculateSubtotalsAndTotal();
    });
});

function calculateSubtotalsAndTotal() {
    // Your calculation logic for subtotals and total
    // Add your calculation logic here
    console.log('Calculating subtotals and total...');
    // Replace the console.log statement with your actual calculation logic
}

function calculateSubtotalsAndTotal() {
    var subTotal = 0;

    $('#product_html tr').each(function() {
        var qty = $(this).find('input[name="product_quantity[]"]').val();
        var price = $(this).find('.price').text();
        var subtotal = qty * price;

        $(this).find('.subtotal').text(subtotal);
        $('#product_subtotal').val(subtotal);

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
}
</script>
@endpush
@endsection
