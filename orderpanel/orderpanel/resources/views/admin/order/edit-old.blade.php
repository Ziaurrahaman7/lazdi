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
                                            {{-- <option value="{{ $product->id}}" @if( $product->id ==
                                                $orderProduct->product_id) selected @endif>{{ $product->title }}
                                            </option> --}}
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
                                                    {{-- <input type="text" name="price_subtotal"
                                                        class="form-control text-end" id="subtotal_price"
                                                        value="{{ $order->price_subtotal }}" readonly> --}}
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