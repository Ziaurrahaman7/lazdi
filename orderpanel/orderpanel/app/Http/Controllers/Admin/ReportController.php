<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Courier;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ReportController extends Controller
{
    public function orderStatuReport(Request $request){

        try {
            if ($request->ajax()) {

                if (isset($request->order_status)){

                    $data = DB::table('orders')
                    ->join('order_shipping', 'order_shipping.order_id', '=', 'orders.id')
                    ->select('orders.order_number','orders.order_number','orders.shiping_date','orders.created_at','orders.price_total','orders.staff_id','orders.product_weight','orders.status','orders.id','orders.order_area','orders.print_status','order_shipping.shipping_phone_number','order_shipping.shipping_address_1','order_shipping.billing_first_name')
                    ->where('orders.status', $request->order_status)
                    ->orderBy('orders.id','DESC')
                    ->get();
                 }
                 if (isset($request->starting_date) && isset($request->ending_date)){
                    $data = DB::table('orders')
                            ->join('order_shipping', 'order_shipping.order_id', '=', 'orders.id')
                            ->select('orders.order_number','orders.order_number','orders.shiping_date','orders.created_at','orders.price_total','orders.staff_id','orders.product_weight','orders.status','orders.print_status','orders.id','orders.order_area','order_shipping.shipping_phone_number','order_shipping.shipping_address_1','order_shipping.billing_first_name')
                        ->whereBetween('orders.shiping_date', [$request->input('starting_date'), $request->input('ending_date')])
                        ->get();
                 }

                if (isset($request->order_id)){
                    $data = DB::table('orders')
                        ->join('order_shipping', 'order_shipping.order_id', '=', 'orders.id')
                        ->select('orders.order_number','orders.order_number','orders.shiping_date','orders.created_at','orders.price_total','orders.staff_id','orders.product_weight','orders.status','orders.print_status','orders.id','orders.order_area','order_shipping.shipping_phone_number','order_shipping.shipping_address_1','order_shipping.billing_first_name')
                        ->where('orders.order_number', $request->order_id)
                        ->get();
                 }

                if (isset($request->order_status) && isset($request->starting_date) && isset($request->ending_date)){
                    $data = DB::table('orders')
                        ->join('order_shipping', 'order_shipping.order_id', '=', 'orders.id')
                        ->select('orders.order_number','orders.order_number','orders.shiping_date','orders.created_at','orders.price_total','orders.staff_id','orders.product_weight','orders.status','orders.print_status','orders.id','orders.order_area','order_shipping.shipping_phone_number','order_shipping.shipping_address_1','order_shipping.billing_first_name')
                        ->where('orders.status', $request->order_status)
                        ->whereBetween('orders.shiping_date', [$request->input('starting_date'), $request->input('ending_date')])
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
                        <span style="color:red;font-weight:bold;position: absolute;margin-top: 25px;">Code : '.$product->product_code.'</span> <br>';
                    }

                    return $details;

                })

                ->addColumn('amount', function ($data) {
                    $price = number_format($data->price_total/100, 2) ?? '--';
                    return $price;
                })

                ->addColumn('status', function ($data) {

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

                    $print = '';

                         if($data->status == 5 || $data->status == 6){
                            if($data->print_status == 2){
                                 $print = '<a id="edit" href="' . route('order-print', $data->id) . ' " class="btn btn-sm btn-danger edit" title="Print"><i class="fa fa-file"></i> re-print</a>';
                            }else if($data->print_status == 1){
                                $print = '<a id="Print" href="' . route('order-print', $data->id) . ' " class="btn btn-sm btn-danger Print" title="Print"><i class="fa fa-file"></i> print</a>';
                                 }
                            }

                        return  $print;

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
                $onlineOrder = DB::table('orders')
                ->join('order_shipping', 'orders.id', '=', 'order_shipping.order_id')
                ->where('orders.buyer_type', "guest")
                ->where('orders.price_total', '>', 0)
                ->whereBetween('orders.status', [1, 10])
                ->count();
                 $offlineOrder = DB::table('orders')
                ->join('order_shipping', 'orders.id', '=', 'order_shipping.order_id')
                ->where('orders.buyer_type', "office")
                ->where('orders.price_total', '>', 0)
                ->whereBetween('orders.status', [1, 10])
                ->count();
             return view('admin.report.status_report', compact('onlineOrder','offlineOrder','new','pendingPayment','pending','courier','delivered','cancelled','pendingInvoice','invoice','booking','return'));

        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function RedxProductList(Request $request)
    {
        try {

            if ($request->ajax()) {

                $data = DB::table('orders')
                ->join('order_shipping', 'order_shipping.order_id', '=', 'orders.id')
                ->select('orders.order_number','orders.order_number','orders.shiping_date','orders.created_at','orders.price_total','orders.staff_id','orders.product_weight','orders.status','orders.id','orders.order_area','order_shipping.shipping_phone_number','order_shipping.shipping_address_1','order_shipping.billing_first_name')
                ->where('orders.courier_service', 5)
                ->orderBy('orders.id','DESC')
                ->get();

                return Datatables::of($data)
                ->addColumn('All', function ($data) {
                    $checkbox = '<input type="checkbox" class="checkbox-row" data-order-id="'.$data->id.'">';
                    return $checkbox;
                })
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
                            $orderNote .= $note->note.'<br>';
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
                            <span style="color:red;font-weight:bold;position: absolute;margin-top: 5px;">Code : '.$product->product_code.'</span> <br>';
                        }

                        return $details;

                    })

                    ->addColumn('amount', function ($data) {
                        $price = number_format($data->price_total/100, 2) ?? '--';
                        return $price;
                    })

                    ->addColumn('status', function ($data) {

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
                        $show = '';
                        return $show ;
                    })

                    ->addIndexColumn()
                    ->rawColumns(['All','orderNumber','courier','officeStaff','customer','product','amount','status','action'])
                    ->toJson();
                }

             return view('admin.report.redx_product_list');
            } catch (\Exception $exception) {
                return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function PathaoProductList(Request $request)
    {
        try {

            if ($request->ajax()) {

                $data = DB::table('orders')
                ->join('order_shipping', 'order_shipping.order_id', '=', 'orders.id')
                ->select('orders.order_number','orders.order_number','orders.shiping_date','orders.created_at','orders.price_total','orders.staff_id','orders.product_weight','orders.status','orders.id','orders.order_area','order_shipping.shipping_phone_number','order_shipping.shipping_address_1','order_shipping.billing_first_name')
                ->where('orders.courier_service', 6)
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
                            <span style="color:red;font-weight:bold;position: absolute;margin-top: 5px;">Code : '.$product->product_code.'</span> <br>';
                        }

                        return $details;

                    })

                    ->addColumn('amount', function ($data) {
                        $price = number_format($data->price_total/100, 2) ?? '--';
                        return $price;
                    })

                    ->addColumn('status', function ($data) {

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
                        $show = '';
                        return $show ;
                    })

                    ->addIndexColumn()
                    ->rawColumns(['orderNumber','courier','officeStaff','customer','product','amount','status','action'])
                    ->toJson();
                }

             return view('admin.report.pathao_product_list');
            } catch (\Exception $exception) {
                return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function OthersProductList(Request $request)
    {
        try {

            if ($request->ajax()) {

                $data = DB::table('orders')
                ->join('order_shipping', 'order_shipping.order_id', '=', 'orders.id')
                ->select('orders.order_number','orders.order_number','orders.shiping_date','orders.created_at','orders.price_total','orders.staff_id','orders.product_weight','orders.status','orders.id','orders.order_area','order_shipping.shipping_phone_number','order_shipping.shipping_address_1','order_shipping.billing_first_name')
                ->whereNotIn('orders.courier_service', [5, 6])
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
                            <span style="color:red;font-weight:bold;position: absolute;margin-top: 5px;">Code : '.$product->product_code.'</span> <br>';
                        }

                        return $details;

                    })

                    ->addColumn('amount', function ($data) {
                        $price = number_format($data->price_total/100, 2) ?? '--';
                        return $price;
                    })

                    ->addColumn('status', function ($data) {

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
                        $show = '';
                        return $show ;
                    })

                    ->addIndexColumn()
                    ->rawColumns(['orderNumber','courier','officeStaff','customer','product','amount','status','action'])
                    ->toJson();
                }

             return view('admin.report.other_product_list');
            } catch (\Exception $exception) {
                return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function steadfastProductList(Request $request)
    {
        try {

            if ($request->ajax()) {

                $data = DB::table('orders')
                ->join('order_shipping', 'order_shipping.order_id', '=', 'orders.id')
                ->select('orders.order_number','orders.order_number','orders.shiping_date','orders.created_at','orders.price_total','orders.staff_id','orders.product_weight','orders.status','orders.id','orders.order_area','order_shipping.shipping_phone_number','order_shipping.shipping_address_1','order_shipping.billing_first_name')
                ->where('orders.courier_service', 7)
                ->where('orders.courier_status', 0)
                ->where('orders.status', '>',3)
                ->orderBy('orders.id','DESC')
                ->get();

                return Datatables::of($data)
                ->addColumn('All', function ($data) {
                    $checkbox = '<input type="checkbox" class="checkbox-row" data-order-id="'.$data->id.'">';
                    return $checkbox;
                })
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
                            $orderNote .= $note->note.'<br>';
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
                            <span style="color:red;font-weight:bold;position: absolute;margin-top: 5px;">Code : '.$product->product_code.'</span> <br>';
                        }

                        return $details;

                    })

                    ->addColumn('amount', function ($data) {
                        $price = number_format($data->price_total/100, 2) ?? '--';
                        return $price;
                    })

                    ->addColumn('status', function ($data) {

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
                        $show = '';
                        return $show ;
                    })

                    ->addIndexColumn()
                    ->rawColumns(['All','orderNumber','courier','officeStaff','customer','product','amount','status','action'])
                    ->toJson();
                }

             return view('admin.report.steadfast_product_list');
            } catch (\Exception $exception) {
                return redirect()->back()->with('error', $exception->getMessage());
        }

        return view('admin.report.steadfast_product_list');
    }
}
