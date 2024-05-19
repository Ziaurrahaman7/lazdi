<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Courier;
use App\Models\CourierBooking;
use App\Models\OrderProduct;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        try {

            if ($request->ajax()) {
                $data = DB::table('orders')
                        ->join('order_shipping', 'order_shipping.order_id', '=', 'orders.id')
                        ->select('orders.order_number','orders.order_number','orders.shiping_date','orders.created_at','orders.price_total','orders.staff_id','orders.product_weight','orders.status','orders.print_status','orders.id','orders.order_area','order_shipping.shipping_phone_number','order_shipping.shipping_address_1','order_shipping.billing_first_name')
                        ->where('orders.status', 1)
                        ->orderBy('orders.id','DESC')
                        ->get();

                return Datatables::of($data)

                    ->addColumn('orderNumber', function ($data) {

                        $date = Carbon::parse($data->created_at)->format('d-m-Y');
                        $time = date('g:i:a', strtotime($data->created_at));

                        $number = '<span class="badge badge-pill badge-danger" style="font-size:18px"> '.$data->order_number.'</span> <br>
                        <span class="badge badge-pill badge-success" style="font-size:18px">'.$date.'</span><br>'.$time.'<br>
                        <span style="color:green"> Shipping Date : <br>'. date('d-m-Y', strtotime($data->created_at)).' </span>';
                        return $number;
                    })

                    ->addColumn('courier', function ($data) {

                        $weight = $data->product_weight ?? '-';
                        $invoice = $data->order_number ?? '-';

                        $courier = '<span style="color:red;font-size: 15px;font-weight: bold"> Weight : '.$weight.'</span> <br> <span style="color:black;font-size: 15px;font-weight: bold"> Invoice : '.$invoice.'</span>';

                        return $courier;
                    })

                    ->addColumn('officeStaff', function ($data) {
                        $user = Auth::user()->username ?? '-';
                        $button = '';
                        if($data->order_area == 1){
                            $button = 'Inside Dhaka';
                        }elseif($data->order_area == 2){
                            $button = 'Outside Dhaka';
                        }

                        $officeStaff ='<input style="width: 15px;text-align: center" type="checkbox" value="73876" class="checkAll ">
                        <span data-toggle="modal" data-target="#modal-edit" onclick="orderEdit(73876)" class="badge badge-pill badge-primary"> '.$user.' </span><br>
                        <span class="badge badge-pill badge-danger"> '.$button.'</span>
                        <span class="badge badge-pill badge-danger"></span>';
                        return $officeStaff;
                    })

                    ->addColumn('customer', function ($data) {

                        $orderNote = '';
                        $notes = DB::table('order_notes')->where('order_id', $data->id)->get();

                        foreach($notes as $key => $note){
                            $orderNote .= $note->note.'('.date('d-m-Y', strtotime($note->created_at)).', '.date('g:i a', strtotime($note->created_at)).')<br>';
                        }

                        $customer ='<span class="badge badge-pill badge-info" style="font-size:18px">'.$data->billing_first_name.'</span>
                        <br>
                        <span class="badge badge-pill badge-success" style="font-size:18px">'.$data->shipping_phone_number.'</span>
                        <br>'.$data->shipping_address_1.'<br>
                        <span style="color:red;font-weight: 400">Note:  '.$orderNote.'</span>';
                        return $customer;
                    })

                    ->addColumn('product', function ($data) {

                        $products = DB::table('order_products')
                            ->join('products','products.id', '=','order_products.product_id')
                            ->join('images','images.product_id', '=','products.id')
                            ->select('products.title','order_products.product_code','order_products.product_unit_price','order_products.product_quantity','order_products.product_total_price','images.image_default')
                            ->where('order_products.order_id', $data->id)
                            ->groupBy('images.product_id')
                            ->get();

                        $details = '';
                        foreach($products as $key => $product){

                            $unitPrice = number_format($product->product_unit_price/100, 2);
                            $totalPrice = number_format($product->product_total_price/100, 2);

                            $details .= ' <br><span class="product-title">'.$product->title.'</span>
                            <img class="img-responsive" width="50" src="' . env('IMAGE_PATH') . $product->image_default . '">
                            <br>
                            <p style="color:red;font-weight:bold;position: absolute;margin-top: 25px;">Code : '.$product->product_code.'</p> <br>';
                        }

                        return $details;

                    })

                    ->addColumn('amount', function ($data) {
                        $price = number_format($data->price_total/100, 2) ?? '--';
                        return $price;
                    })

                    ->addColumn('status', function ($data) {
                         $button = '';
                        if($data->status == 1){
                            $button = '<span class="badge badge-primary" title="New">New</span>';
                        }elseif($data->status == 2){
                            $button = '<span class="badge badge-primary" title="Pending for Payment">Pending for Payment</span>';
                        }
                        elseif($data->status == 3){
                            $button = '<span class="badge badge-primary" title="Pending">Pending</span>';
                        }
                        elseif($data->status == 4){
                            $button = '<span class="badge badge-primary" title="Courier">Courier</span>';
                        }
                        elseif($data->status == 5){
                            $button = '<span class="badge badge-primary" title="Invoice">Invoice</span>';
                        }
                        elseif($data->status == 6){
                            $button = '<span class="badge badge-primary" title="Pending Invoice">Pending Invoice</span>';
                        }
                        elseif($data->status == 7){
                            $button = '<span class="badge badge-primary" title="Delivered">Delivered</span>';
                        }
                        elseif($data->status == 8){
                            $button = '<span class="badge badge-primary" title="Booking">Booking</span>';
                        }
                        elseif($data->status == 9){
                            $button = '<span class="badge badge-primary" title="Return">Return</span>';
                        }
                        elseif($data->status == 10){
                            $button = '<span class="badge badge-primary" title="Cancel">Cancel</span>';
                        }
                        return $button;
                    })


                    ->addColumn('action', function ($data) {

                        $edit = '<a id="edit" href="' . route('order.edit', $data->id) . ' " class="btn btn-sm btn-primary edit" title="Edit"><i class="fa fa-edit"></i></a>';
                        $print = '';


                        if($data->print_status == 2){
                            $print = '<a id="edit" href="' . route('order-print', $data->id) . ' " class="btn btn-sm btn-danger edit" title="Edit"><i class="fa fa-file"></i> re-print</a>';
                        }else if($data->print_status == 1){
                            $print = '<a id="edit" href="' . route('order-print', $data->id) . ' " class="btn btn-sm btn-danger edit" title="Edit"><i class="fa fa-file"></i> print</a>';
                        }

                        if($data->status == 5 || $data->status == 6){
                            return $edit . $print;
                        }else{
                            return $edit;
                        }

                    })

                    ->addIndexColumn()
                    ->rawColumns(['orderNumber','courier','officeStaff','customer','product','amount','status','action'])
                    ->toJson();
                }

                // $new = DB::table('orders')->where('status', 1)->where('price_total', '>', 0)->count();
                $new = DB::table('orders')
                ->join('order_shipping', 'orders.id', '=', 'order_shipping.order_id')
                ->where('orders.status', 1)
                ->where('orders.price_total', '>', 0)
                ->count();

                $pendingPayment = DB::table('orders')
                ->join('order_shipping', 'orders.id', '=', 'order_shipping.order_id')
                ->where('orders.status', 2)
                ->where('orders.price_total', '>', 0)
                ->count();
                
                $pending = DB::table('orders')
                ->join('order_shipping', 'orders.id', '=', 'order_shipping.order_id')
                ->where('orders.status', 3)
                ->where('orders.price_total', '>', 0)
                ->count();
                $courier = DB::table('orders')
                ->join('order_shipping', 'orders.id', '=', 'order_shipping.order_id')
                ->where('orders.status', 4)
                ->where('orders.price_total', '>', 0)
                ->count();
                $invoice = DB::table('orders')
                ->join('order_shipping', 'orders.id', '=', 'order_shipping.order_id')
                ->where('orders.status', 5)
                ->where('orders.price_total', '>', 0)
                ->count();
                $pendingInvoice = DB::table('orders')
                ->join('order_shipping', 'orders.id', '=', 'order_shipping.order_id')
                ->where('orders.status', 6)
                ->where('orders.price_total', '>', 0)
                ->count();
                $delivered = DB::table('orders')
                ->join('order_shipping', 'orders.id', '=', 'order_shipping.order_id')
                ->where('orders.status', 7)
                ->where('orders.price_total', '>', 0)
                ->count();
                $booking = DB::table('orders')
                ->join('order_shipping', 'orders.id', '=', 'order_shipping.order_id')
                ->where('orders.status', 8)
                ->where('orders.price_total', '>', 0)
                ->count();
                $return = DB::table('orders')
                ->join('order_shipping', 'orders.id', '=', 'order_shipping.order_id')
                ->where('orders.status', 9)
                ->where('orders.price_total', '>', 0)
                ->count();
                $cancelled = DB::table('orders')
                ->join('order_shipping', 'orders.id', '=', 'order_shipping.order_id')
                ->where('orders.status', 10)
                ->where('orders.price_total', '>', 0)
                ->count();
                $users = DB::table('users')->get();
             return view('admin.order.index', compact('new','pendingPayment','pending','courier','delivered','cancelled','pendingInvoice','invoice','booking','return','users'));
            } catch (\Exception $exception) {
                return redirect()->back()->with('error', $exception->getMessage());
            }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = DB::table('products')->where('status', 1)->get();
        $couriers = Courier::where('status',1)->get();
        return view('admin.order.create', compact('products','couriers'));
    }

    public function ProductInfo(Request $request)
    {
        $product = DB::table('products')
        ->join('images', 'images.product_id', '=', 'products.id')
        ->select('images.image_default','products.title','products.sku','products.price','products.id','products.discount_rate')
        ->where('products.id', $request->id)
        ->first();

        $discount = $product->discount_rate;
        $productPrice = $product->price/100;
        $discountPrice = ($productPrice * $discount)/100;
        $productUnitPrice = $productPrice - $discountPrice;

        $html = '<tr>
                    <td>' . $product->title . '</td>
                    <td>' . $product->sku . '</td>
                    <td><img class="img-responsive" width="50" src="' . env('IMAGE_PATH') . $product->image_default . '"></td>
                    <td><input type="number" name="product_quantity[]" id="qty" value="1" class="form-control" placeholder="Enter qty"></td>
                    <td class="price">' . $productUnitPrice . '</td>
                    <td class="text-right"><p class="subtotal">' . $product->price . '</p>

                    <input type="hidden" name="product_code[]" class="form-control" value="' . $product->sku . '" readonly>

                    <input type="hidden" name="product_total_price[]" id="product_total_price" class="form-control product_total">
                     </td>

                    <input type="hidden" name="product_unit_price[]"  value="' . $productUnitPrice . '">
                </tr>';

        return response()->json(['html' => $html, 'product' => $product]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'billing_name' => 'required',
            'billing_mobile' => 'required',
            'shipping_address_1' => 'required',
            'order_area' => 'required',
            'product_weight' => 'required',
            // 'order_number' => 'required',
            'order_status' => 'required',
            'shiping_date' => 'required',
            'product_id' => 'required',
        ]);

        DB::beginTransaction();

        try {

            DB::table('orders')->insert([
                [
                    'order_number' => $request->order_number,
                    'buyer_id' => 0,
                    'buyer_type' => 'office',
                    'price_vat' => 0,
                    'price_currency' => 'BDT',
                    'status' => $request->order_status,
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                    'created_at' => now()->format('Y-m-d H:i:s'),
                    'shiping_date' => $request->shiping_date,
                    'staff_id' => Auth::user()->id ?? '',
                    'order_note' => $request->order_note,
                    'order_area' => $request->order_area,
                    'courier_service' => $request->courier_service,
                    'area_id' => $request->area_id,
                    'product_weight' => $request->product_weight,
                    'customDiscount' => ($request->discount_price) * 100,
                    'price_subtotal' => ($request->price_subtotal) * 100,
                    'price_total' => ($request->price_total) * 100,
                    'advance_price' => ($request->advance_price) * 100,
                ],
            ]);

            $order = DB::table('orders')->latest()->first();

            $orderNote = DB::table('order_notes')->insert([
                [
                    'order_id' => $order->id,
                    'note' => $request->order_note,
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                    'created_at' => now()->format('Y-m-d H:i:s'),
                    'created_by' => Auth::user()->id ?? '',
                ],
            ]);

            $orderShipping = DB::table('order_shipping')->insert([
                [
                    'order_id' => $order->id,
                    'billing_first_name' => $request->billing_name,
                    'shipping_phone_number' => $request->billing_mobile,
                    'shipping_address_1' => $request->shipping_address_1,
                    'delivery_cost' => ($request->price_shipping),
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                    'created_at' => now()->format('Y-m-d H:i:s'),
                    'created_by' => Auth::user()->id ?? '',
                ],
            ]);

            foreach($request->product_id as $key => $id){
                $product['product_id'] = $id;
                $product['order_id'] = $order->id;
                $product['product_currency'] = 'BDT';
                $product['product_quantity'] = $request->product_quantity[$key];
                $product['product_unit_price'] = ($request->product_unit_price[$key]) * 100;
                $product['product_total_price'] = ($request->product_total_price[$key]) * 100;
                $product['product_code'] = $request->product_code[$key];
                $product['shiping_date'] = now()->format('Y-m-d');
                $product['staff_id'] = Auth::user()->id ?? '';
                DB::table('order_products')->insert($product);
            }

            DB::commit();

            return redirect()->route('order.index')
                    ->with('success', 'Order Added Successfully');
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $data = DB::table('orders')->where('id', $id)->first();
        // $customer = DB::table('order_shipping')->where('order_id', $id)->first();
        // $note = DB::table('order_notes')->where('order_id', $id)->first();
        // $products = DB::table('order_products')->where('order_id', $id)->get();
        // return view('admin.order.show', compact('data','customer','products','note'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order = DB::table('orders')->where('id', $id)->first();
        $customer = DB::table('order_shipping')->where('order_id', $id)->first();
        $note = DB::table('order_notes')->where('order_id', $id)->get();
        // $orderProducts = DB::table('order_products')->where('order_id', $id)->get();
        $orderProducts = DB::table('order_products')
        ->select('*', DB::raw('SUM(product_quantity) as product_quantity'))
        ->where('order_id', $id)
        ->groupBy('order_id', 'product_id')
        ->get();
        $couriers = Courier::where('status',1)->get();
        $products = DB::table('products')->get();
        return view('admin.order.edit', compact('order','customer','products','note','orderProducts','couriers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'billing_name' => 'required',
            'billing_mobile' => 'required',
            'shipping_address_1' => 'required',
            'product_weight' => 'required',
            // 'order_number' => 'required',
            'order_status' => 'required',
            'product_id' => 'required',
        ]);

        DB::beginTransaction();

        try {

            DB::table('orders')
                ->where('id', $id)
                ->update([
                    'buyer_id' => 0,
                    'price_vat' => 0,
                    'price_currency' => 'BDT',
                    'status' => $request->order_status,
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                    'created_at' => now()->format('Y-m-d H:i:s'),
                    'shiping_date' => $request->shiping_date,
                    'staff_id' => Auth::user()->id ?? '',
                    'order_note' => $request->order_note,
                    'order_area' => $request->order_area,
                    'courier_service' => $request->courier_service,
                    'area_id' => $request->area_id,
                    'product_weight' => $request->product_weight,
                    'order_number' => $request->order_number,
                    'customDiscount' => ($request->discount_price) * 100,
                    'price_subtotal' => ($request->price_subtotal) * 100,
                    'price_total' => ($request->price_total) * 100,
                    'advance_price' => ($request->advance_price) * 100,
            ]);



            $order = DB::table('orders')->where('id', $id)->first();

            $courier = CourierBooking::where('active',1)->where('order_id',$id)->first();
            if($courier){
               if($courier->courier_id != $request->courier_service){
                $courier->active=0;
                $courier->status="Cancel By Admin";
                $courier->save();
                DB::table('orders')->where('id', $id)->update(['courier_status' => 0,]);
               }
            }


            $orderNote = DB::table('order_notes')->insert([
                [
                    'order_id' => $order->id,
                    'note' => $request->order_note,
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                    'created_at' => now()->format('Y-m-d H:i:s'),
                    'created_by' => Auth::user()->id ?? '',
                ],
            ]);

            DB::table('order_shipping')
                        ->where('order_id', $id)
                        ->update([
                                'billing_first_name' => $request->billing_name,
                                'shipping_phone_number' => $request->billing_mobile,
                                'shipping_address_1' => $request->shipping_address_1,
                                'delivery_cost' => ($request->price_shipping),
                                'updated_at' => now()->format('Y-m-d H:i:s'),
                                'created_at' => now()->format('Y-m-d H:i:s'),
                                'created_by' => Auth::user()->id ?? '',
                        ]);

            $order = DB::table('orders')->latest()->first();
            DB::table('order_products')->where('order_id', $id)->delete();

            foreach($request->product_id as $key => $id){
                $product['product_id'] = $id;
                $product['order_id'] = $order->id;
                $product['product_currency'] = 'BDT';
                $product['product_quantity'] = $request->product_quantity[$key];
                $product['product_unit_price'] = ($request->product_unit_price[$key]) * 100;
                $product['product_total_price'] = ($request->product_total_price[$key]) * 100;
                $product['product_code'] = $request->product_code[$key];
                $product['shiping_date'] = now()->format('Y-m-d');
                $product['staff_id'] = Auth::user()->id ?? '';
                DB::table('order_products')->insert($product);
            }

            DB::commit();

            return redirect()->route('order.index')
                    ->with('success', 'Order Updated Successfully');
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        try {
            $order = DB::table('orders')->where('id', $id)->delete();
            // $customer = DB::table('order_shipping')->where('order_id', $id)->delete();
            // $note = DB::table('order_notes')->where('order_id', $id)->delete();
            // $orderProducts = DB::table('order_products')->where('order_id', $id)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Order deleted successfully',
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Order deleted failed',
            ]);
        }
    }


    public function StatusWiseProduct(Request $request)
    {
        try {
            if ($request->ajax()) {

                $data = DB::table('orders')
                ->join('order_shipping', 'order_shipping.order_id', '=', 'orders.id')
                ->select('orders.order_number','orders.order_number','orders.shiping_date','orders.created_at','orders.price_total','orders.staff_id','orders.product_weight','orders.status','orders.id','orders.order_area','orders.print_status','order_shipping.shipping_phone_number','order_shipping.shipping_address_1','order_shipping.billing_first_name')
                ->where('orders.status', $request->id)
                 ->orWhere('orders.buyer_type', $request->buyer_type)
                 ->where('orders.price_total', '>', 0)
                ->orderBy('orders.id','DESC')
                ->get();
                    
                return Datatables::of($data)

                    ->addColumn('orderNumber', function ($data) {

                        $date = Carbon::parse($data->created_at)->format('d-m-Y');
                        $time = date('g:i:a', strtotime($data->created_at));

                        $number = '<span class="badge badge-pill badge-danger" style="font-size:18px"> '.$data->order_number.'</span> <br>
                        <span class="badge badge-pill badge-success" style="font-size:18px">'.$date.'</span><br>'.$time.'<br>
                        <span style="color:green"> Shipping Date : <br>'. date('d-m-Y', strtotime($data->created_at)).' </span>';
                        return $number;
                    })

                    ->addColumn('courier', function ($data) {

                        $weight = $data->product_weight ?? '-';
                        $invoice = $data->order_number ?? '-';

                        $courier = '<span style="color:red;font-size: 15px;font-weight: bold"> Weight : '.$weight.'</span> <br> <span style="color:black;font-size: 15px;font-weight: bold"> Invoice : '.$invoice.'</span>';

                        return $courier;
                    })

                    ->addColumn('officeStaff', function ($data) {
                        $user = Auth::user()->name ?? '-';
                        $button = '';
                        if($data->order_area == 1){
                            $button = 'Inside Dhaka';
                        }elseif($data->order_area == 2){
                            $button = 'Outside Dhaka';
                        }

                        $officeStaff ='<input style="width: 15px;text-align: center" type="checkbox" value="73876" class="checkAll ">
                        <span data-toggle="modal" data-target="#modal-edit" onclick="orderEdit(73876)" class="badge badge-pill badge-primary"> '.$user.' </span><br>
                        <span class="badge badge-pill badge-danger"> '.$button.'</span>
                        <span class="badge badge-pill badge-danger"></span>';
                        return $officeStaff;
                    })

                    ->addColumn('customer', function ($data) {

                        $orderNote = '';
                        $notes = DB::table('order_notes')->where('order_id', $data->id)->get();

                        foreach($notes as $key => $note){
                            $orderNote .=$note->note.'<br>';
                        }

                        $customer ='<span class="badge badge-pill badge-info" style="font-size:18px">'.$data->billing_first_name.'</span>
                        <br>
                        <span class="badge badge-pill badge-success" style="font-size:18px">'.$data->shipping_phone_number.'</span>
                        <br>'.$data->shipping_address_1.'<br>
                        <span style="color:red;font-weight: 400">Note:  '.$orderNote.'</span>';
                        return $customer;
                    })

                    ->addColumn('product', function ($data) {

                        $products = DB::table('order_products')
                            ->join('products','products.id', '=','order_products.product_id')
                            ->join('images','images.product_id', '=','products.id')
                            ->select('products.title','order_products.product_code','order_products.product_unit_price','order_products.product_quantity','order_products.product_total_price','images.image_default')
                            ->where('order_products.order_id', $data->id)
                            ->groupBy('images.product_id')
                            ->get();

                        $details = '';

                        foreach($products as $key => $product){

                            $unitPrice = number_format($product->product_unit_price, 2);
                            $totalPrice = number_format($product->product_total_price, 2);

                            $details .= ' <br><span class="product-title">'.$product->title.'</span>
                            <img class="img-responsive" width="50" src="' . env('IMAGE_PATH') . $product->image_default . '">
                            <br>
                            <p style="color:red;font-weight:bold;position: absolute;margin-top: 25px;">Code : '.$product->product_code.'</p> <br>';
                        }

                        return $details;

                    })

                    ->addColumn('amount', function ($data) {
                        $price = number_format($data->price_total/100, 2) ?? '--';
                        return $price;
                    })

                    ->addColumn('status', function ($data) {
                        $button = '';
                        if($data->status == 1){
                            $button = '<span class="badge badge-primary" title="New">New</span>';
                        }elseif($data->status == 2){
                            $button = '<span class="badge badge-primary" title="Pending for Payment">Pending for Payment</span>';
                        }
                        elseif($data->status == 3){
                            $button = '<span class="badge badge-primary" title="Pending">Pending</span>';
                        }
                        elseif($data->status == 4){
                            $button = '<span class="badge badge-primary" title="Courier">Courier</span>';
                        }
                        elseif($data->status == 5){
                            $button = '<span class="badge badge-primary" title="Invoice">Invoice</span>';
                        }
                        elseif($data->status == 6){
                            $button = '<span class="badge badge-primary" title="Pending Invoice">Pending Invoice</span>';
                        }
                        elseif($data->status == 7){
                            $button = '<span class="badge badge-primary" title="Delivered">Delivered</span>';
                        }
                        elseif($data->status == 8){
                            $button = '<span class="badge badge-primary" title="Booking">Booking</span>';
                        }
                        elseif($data->status == 9){
                            $button = '<span class="badge badge-primary" title="Return">Return</span>';
                        }
                        elseif($data->status == 10){
                            $button = '<span class="badge badge-primary" title="Cancel">Cancel</span>';
                        }
                        return $button;
                    })


                    ->addColumn('action', function ($data) {
                     $edit = '<a id="edit" href="' . route('order.edit', $data->id) . ' " class="btn btn-sm btn-primary edit" title="Edit"><i class="fa fa-edit"></i></a>';
                        $print = '';

                        if($data->status == 5 || $data->status == 6){
                            if($data->print_status == 2){
                                 $print = '<a id="edit" href="' . route('order-print', $data->id) . ' " class="btn btn-sm btn-danger edit" title="Print"><i class="fa fa-file"></i> re-print</a>';
                            }else if($data->print_status == 1){
                                $print = '<a id="Print" href="' . route('order-print', $data->id) . ' " class="btn btn-sm btn-danger Print" title="Print"><i class="fa fa-file"></i> print</a>';
                                 }
                            }

                        return  $print .$edit;

                    })

                    ->addIndexColumn()
                    ->rawColumns(['orderNumber','courier','officeStaff','customer','product','amount','status','action'])
                    ->toJson();
                }

                } catch (\Exception $exception) {
                    return redirect()->back()->with('error', $exception->getMessage());
            }
    }

    public function showProductSales()
    {
        $productQuantities = $this->getTodaySales();
        return view('admin.order.order-products', compact('productQuantities'));
    }

    private function getTodaySales()
   {
    // Get today's date
    $today = Carbon::today();

    // Get the orders and products sold today with product details
    $todayOrders = OrderProduct::whereDate('order_products.created_at', $today)
        ->join('products', 'order_products.product_id', '=', 'products.id')
        ->select('products.title', 'products.sku', 'order_products.product_quantity')
        ->get();

    // Initialize an array to store the product quantity sold
    $productQuantities = [];

    // Calculate the total quantity of each product sold today
    foreach ($todayOrders as $order) {
        $productId = $order->product_id;
        $quantity = $order->product_quantity;

        // If the product title and SKU are not already in the array, initialize them with the quantity
        if (!isset($productQuantities[$order->title])) {
            $productQuantities[$order->title] = [
                'sku' => $order->sku,
                'quantity' => $quantity
            ];
        } else {
            // If the product title and SKU are already in the array, add the quantity to the existing value
            $productQuantities[$order->title]['quantity'] += $quantity;
        }
    }

    // Now $productQuantities contains the product title, SKU, and quantity of each product sold today
    // You can return it or use it as needed.
    return $productQuantities;
}
// ============================date base filtaring ================
public function getOrderProductReport(Request $request)
{
    $productQuantities = $this->getFilteredSales($request);
    // dd($productQuantities);
    return view('admin.order.order-products', compact('productQuantities'));
}

private function getFilteredSales(Request $request)
{
    // Get the input values from the request
    $startDate = $request->input('order_date_start');
    $endDate = $request->input('order_date_end');
    $productCode = $request->input('product_code');

    // Get the orders and products sold based on date range and SKU with product details
    $query = OrderProduct::query();

    if ($startDate) {
        $query->whereDate('order_products.created_at', '>=', $startDate);
    }

    if ($endDate) {
        $query->whereDate('order_products.created_at', '<=', $endDate);
    }

    if ($productCode) {
        $query->whereHas('product', function ($query) use ($productCode) {
            $query->where('sku', 'LIKE', '%' . $productCode . '%');
        });
    }

    $filteredOrders = $query->with('product')
        ->select('product_id', 'product_quantity')
        ->get();

    // Initialize an array to store the product quantity sold
    $productQuantities = [];

    // Calculate the total quantity of each product sold within the date range and matching SKU
    foreach ($filteredOrders as $order) {
        $productId = $order->product_id;
        $quantity = $order->product_quantity;

        $productTitle = $order->product->title;
        $sku = $order->product->sku;

        // If the product title and SKU are not already in the array, initialize them with the quantity
        if (!isset($productQuantities[$productTitle])) {
            $productQuantities[$productTitle] = [
                'sku' => $sku,
                'quantity' => $quantity
            ];
        } else {
            // If the product title and SKU are already in the array, add the quantity to the existing value
            $productQuantities[$productTitle]['quantity'] += $quantity;
        }
    }

    // Now $productQuantities contains the product title, SKU, and quantity of each product sold within the date range and matching SKU
    // You can return it or use it as needed.
    return $productQuantities;
}

public function currentMonthstaffReport(Request $request){
    $reports = $this->getOrderStatusCountsForAllUsers($request);
    // dd($reports);
   return view('admin.order.currentMonthStaffReport', compact('reports'));
}

private function getOrderStatusCountsForAllUsers()
{
    $currentMonth = Carbon::now()->startOfMonth();

    $statusCounts = User::leftJoin('orders', 'users.id', '=', 'orders.staff_id')
        ->select(
            'users.id as user_id','users.username as username',
            DB::raw('SUM(status = 1) as new'),
            DB::raw('SUM(status = 2) as pendingPayment'),
            DB::raw('SUM(status = 3) as pending'),
            DB::raw('SUM(status = 4) as courier'),
            DB::raw('SUM(status = 5) as delivered'),
            DB::raw('SUM(status = 6) as cancelled'),
            DB::raw('SUM(status = 7) as pendingInvoice'),
            DB::raw('SUM(status = 8) as invoice'),
            DB::raw('SUM(status = 9) as booking'),
            DB::raw('SUM(status = 10) as delivered')
        )
        ->where('orders.created_at', '>=', $currentMonth)
        ->groupBy('users.id')
        ->get()
        ->toArray();

    return $statusCounts;
}

    public function OrderPrint($id){
            $siteInfo = DB::table('settings')->first();
            $logo = DB::table('general_settings')->first('logo');
            $order = DB::table('orders')->where('id', $id)->first();
            DB::table('orders') ->where('id', $id) ->update([ 'print_status' => 2, 'status'=> 5, ]);
            $customer = DB::table('order_shipping')->where('order_id', $id)->first();
            $note = DB::table('order_notes')->where('order_id', $id)->get();
            $products = DB::table('order_products')
                            ->join('products','products.id', '=','order_products.product_id')
                            ->join('images','images.product_id', '=','products.id')
                            ->select('products.title','order_products.product_code','order_products.product_unit_price','order_products.product_quantity','order_products.product_total_price','images.image_default')
                            ->where('order_products.order_id', $order->id)
                            ->groupBy('images.product_id')
                            ->get();

            return view('admin.order.print', compact('order','customer','products','note','siteInfo','logo'));
    }

    public function OrderWiseProduct(Request $request)
    {
        try {
            if ($request->ajax()) {

                if (isset($request->order_id)){
                    $data = DB::table('orders')
                    ->join('order_shipping', 'order_shipping.order_id', '=', 'orders.id')
                    ->select('orders.order_number','orders.order_number','orders.shiping_date','orders.created_at','orders.price_total','orders.staff_id','orders.product_weight','orders.status','orders.id','orders.order_area','order_shipping.shipping_phone_number','order_shipping.shipping_address_1','order_shipping.billing_first_name')
                    ->where('orders.order_number', $request->order_id)
                    ->orderBy('orders.id','DESC')
                    ->get();
                 }

                 if (isset($request->phone)){
                    $data = DB::table('orders')
                    ->join('order_shipping', 'order_shipping.order_id', '=', 'orders.id')
                    ->select('orders.order_number','orders.order_number','orders.shiping_date','orders.created_at','orders.price_total','orders.staff_id','orders.product_weight','orders.status','orders.id','orders.order_area','order_shipping.shipping_phone_number','order_shipping.shipping_address_1','order_shipping.billing_first_name')
                    ->where('order_shipping.shipping_phone_number', $request->phone)
                    ->orderBy('orders.id','DESC')
                    ->get();
                 }

                return Datatables::of($data)

                    ->addColumn('orderNumber', function ($data) {

                        $date = Carbon::parse($data->created_at)->format('d-m-Y');
                        $time = date('g:i:a', strtotime($data->created_at));

                        $number = '<span class="badge badge-pill badge-danger" style="font-size:18px"> '.$data->order_number.'</span> <br>
                        <span class="badge badge-pill badge-success" style="font-size:18px">'.$date.'</span><br>'.$time.'<br>
                        <span style="color:green"> Shipping Date : <br>'. date('d-m-Y', strtotime($data->created_at)).' </span>';
                        return $number;
                    })

                    ->addColumn('courier', function ($data) {

                        $weight = $data->product_weight ?? '-';
                        $invoice = $data->order_number ?? '-';

                        $courier = '<span style="color:red;font-size: 15px;font-weight: bold"> Weight : '.$weight.'</span> <br> <span style="color:black;font-size: 15px;font-weight: bold"> Invoice : '.$invoice.'</span>';

                        return $courier;
                    })

                    ->addColumn('officeStaff', function ($data) {
                        $user = Auth::user()->name ?? '-';
                        $button = '';
                        if($data->order_area == 1){
                            $button = 'Inside Dhaka';
                        }elseif($data->order_area == 2){
                            $button = 'Outside Dhaka';
                        }

                        $officeStaff ='<input style="width: 15px;text-align: center" type="checkbox" value="73876" class="checkAll ">
                        <span data-toggle="modal" data-target="#modal-edit" onclick="orderEdit(73876)" class="badge badge-pill badge-primary"> '.$user.' </span><br>
                        <span class="badge badge-pill badge-danger"> '.$button.'</span>
                        <span class="badge badge-pill badge-danger"></span>';
                        return $officeStaff;
                    })

                    ->addColumn('customer', function ($data) {

                        $orderNote = '';
                        $notes = DB::table('order_notes')->where('order_id', $data->id)->get();

                        foreach($notes as $key => $note){
                            $orderNote .=$note->note.'<br>';
                        }

                        $customer ='<span class="badge badge-pill badge-info" style="font-size:18px">'.$data->billing_first_name.'</span>
                        <br>
                        <span class="badge badge-pill badge-success" style="font-size:18px">'.$data->shipping_phone_number.'</span>
                        <br>'.$data->shipping_address_1.'<br>
                        <span style="color:red;font-weight: 400">Note:  '.$orderNote.'</span>';
                        return $customer;
                    })

                    ->addColumn('product', function ($data) {

                        $products = DB::table('order_products')
                            ->join('products','products.id', '=','order_products.product_id')
                            ->join('images','images.product_id', '=','products.id')
                            ->select('products.title','order_products.product_code','order_products.product_unit_price','order_products.product_quantity','order_products.product_total_price','images.image_default')
                            ->where('order_products.order_id', $data->id)
                            ->groupBy('images.product_id')
                            ->get();

                        $details = '';

                        foreach($products as $key => $product){

                            $unitPrice = number_format($product->product_unit_price, 2);
                            $totalPrice = number_format($product->product_total_price, 2);

                            $details .= ' <br><span class="product-title">'.$product->title.'</span>
                            <img class="img-responsive" width="50" src="' . env('IMAGE_PATH') . $product->image_default . '">
                            <br>
                            <p style="color:red;font-weight:bold;position: absolute;margin-top: 25px;">Code : '.$product->product_code.'</p> <br>';
                        }

                        return $details;

                    })

                    ->addColumn('amount', function ($data) {
                        $price = number_format($data->price_total/100, 2) ?? '--';
                        return $price;
                    })

                    ->addColumn('status', function ($data) {
                        $button = '';
                        if($data->status == 1){
                            $button = '<span class="badge badge-primary" title="New">New</span>';
                        }elseif($data->status == 2){
                            $button = '<span class="badge badge-primary" title="Pending for Payment">Pending for Payment</span>';
                        }
                        elseif($data->status == 3){
                            $button = '<span class="badge badge-primary" title="Pending">Pending</span>';
                        }
                        elseif($data->status == 4){
                            $button = '<span class="badge badge-primary" title="Courier">Courier</span>';
                        }
                        elseif($data->status == 5){
                            $button = '<span class="badge badge-primary" title="Invoice">Invoice</span>';
                        }
                        elseif($data->status == 6){
                            $button = '<span class="badge badge-primary" title="Pending Invoice">Pending Invoice</span>';
                        }
                        elseif($data->status == 7){
                            $button = '<span class="badge badge-primary" title="Delivered">Delivered</span>';
                        }
                        elseif($data->status == 8){
                            $button = '<span class="badge badge-primary" title="Booking">Booking</span>';
                        }
                        elseif($data->status == 9){
                            $button = '<span class="badge badge-primary" title="Return">Return</span>';
                        }
                        elseif($data->status == 10){
                            $button = '<span class="badge badge-primary" title="Cancel">Cancel</span>';
                        }
                        return $button;
                    })


                    ->addColumn('action', function ($data) {

                        $edit = '<a id="edit" href="' . route('order.edit', $data->id) . ' " class="btn btn-sm btn-primary edit" title="Edit"><i class="fa fa-edit"></i></a> ';
                        return $edit;

                    })

                    ->addIndexColumn()
                    ->rawColumns(['orderNumber','courier','officeStaff','customer','product','amount','status','action'])
                    ->toJson();
                }

                } catch (\Exception $exception) {
                    return redirect()->back()->with('error', $exception->getMessage());
            }
      }
}
