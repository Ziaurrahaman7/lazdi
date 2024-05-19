<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Courier;
use App\Models\CourierBooking;
use App\Models\Order;
use App\Models\OrderShipping;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Yajra\DataTables\Facades\DataTables;

class CourierController extends Controller
{

    public function index()
    {
        //
    }

    public function handleCourierCheck(Request $request)
{
    $data='Not Found';
    $result = CourierBooking::where('active', 1)
            ->where(function ($q) use ($request) {
                $q->where('invoice', $request->input('check'))
                    ->orWhere('consignment_id',$request->input('check'))
                    ->orWhere('tracking_code', $request->input('check'));
            })
            ->first();
    if($result){
        if ($result->invoice == $request->input('check')) {
            $delivery = $this->invoiceIdByStatusCheck($result->invoice);
        } elseif ($result->consignment_id == $request->input('check')) {
            $delivery = $this->consignmentIdByCheck($result->consignment_id);
        } elseif ($result->tracking_code == $request->input('check')) {
            $delivery = $this->trackingCodeCheck($result->tracking_code);
        }
        $allBooked =CourierBooking::with('order')->with('courier')->with('order.order_products.products')->with('order.order_shipping')->where('order_id', @$result->order_id)->get();
        $data='<h2 class="text-center" >Delivery Status:'. $delivery .' </h2> <table class="table"><thead><tr><th>Order</th><th>Courier</th><th>Customer</th></tr></thead><tbody>';
        if($allBooked){
            foreach ($allBooked as $value) {
                $wOrder=@$value->order_id;
                $wConsignment=@$value->consignment_id;
                $wTracking=@$value->tracking_code;
                $wInvoice=@$value->invoice;
                $wActive=@$value->active?"Processing":"Cancel by Admin";
                $wStatus=@$value->status;
                $wCurrierName=@$value->courier->name;
                $wCustomerName=@$value->order_shipping->billing_first_name.' '.@$value->order_shipping->billing_last_name;
                $wCustomerNumber=@$value->order_shipping->shipping_phone_number;
                // $wStatus=@$value->order->product;
                
                $data .="<tr>
                <td>
                Order: $wOrder
                </td>
                <td>
                   CurrierName: $wCurrierName   <br>  Invoice: $wInvoice   <br> Consignment: $wConsignment  <br> Tracking: $wTracking  <br> Status: $wStatus
                </td>
                <td>
                   CustomerName: $wCustomerName  <br> CustomerNumber: $wCustomerNumber
                </td>
                </tr>";
            }
        }
        $data .='</tbody></table>';
    }


    $response =  $data; //$result->status;


    // Return the response (this will be displayed in the 'responseDiv')
    return $response;
}

    public function productCheck(Request $request)
    {
        return view('admin.report.courier_product_check');
    }


    public function CourierBooked(Request $request)
    {
        try {

            if ($request->ajax()) {

                $data = DB::table('orders')
                ->join('order_shipping', 'order_shipping.order_id', '=', 'orders.id')
                ->join('courier_bookings', 'courier_bookings.order_id', '=', 'orders.id')
                ->select('orders.order_number','orders.order_number','orders.shiping_date','orders.created_at','orders.price_total','orders.staff_id','orders.product_weight','orders.status','orders.id','orders.order_area','order_shipping.shipping_phone_number','order_shipping.shipping_address_1','order_shipping.billing_first_name','courier_bookings.invoice as cinvoice','courier_bookings.consignment_id','courier_bookings.tracking_code','courier_bookings.details','courier_bookings.created_at as cCreated')
                ->where('orders.courier_status', 1)
                ->where('orders.status', '<', 7)
                ->orderBy('orders.id','DESC')
                ->get();

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('order', function ($data) {

                        $date = Carbon::parse($data->created_at)->format('d-m-Y');
                        $time = date('g:i:a', strtotime($data->created_at));

                        $number = '<span class="badge badge-pill badge-danger" style="font-size:18px"> '.$data->order_number.'</span> <br>
                        <span class="badge badge-pill badge-success" style="font-size:18px">'.$date.'</span><br>'.$time.'<br>
                        <span style="color:green"> Shipping Date : <br>'. date('d-m-Y', strtotime($data->created_at)).' </span>';
                        return $number;
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
                            <img  width="50" src="' . env('IMAGE_PATH') . $product->image_default . '">
                            <br>
                            <p style="color:red;font-weight:bold;position: absolute;margin-top: 25px;">Code : '.$product->product_code.'</p> <br>';
                        }

                        return $details;

                    })

                    ->addColumn('courier', function ($data) {

                        $weight = $data->product_weight ?? '-';
                        $invoice = $data->order_number ?? '-';
                        $cinvoice=$data->cinvoice;
                        $consignment=$data->consignment_id;
                        $tracking=$data->tracking_code;

                        $courier = '<span style="color:red;font-size: 15px;font-weight: bold"> Weight : '.$weight.'</span> <br> <span style="color:black;font-size: 15px;font-weight: bold"> Invoice : '.$invoice. "</span>
                        <p><strong> Courier Invoice:</strong> $cinvoice </p>
                        <p><strong>  Consignment Id: </strong> $consignment </p>
                        <p><strong>  Tracking Code: </strong> $tracking </p> ";

                        return $courier;
                    })


                    ->addColumn('booking', function ($data) {
                        return $data->cCreated;
                    })

                    ->addColumn('action', function ($data) {
                        $cinvoice=$data->cinvoice;
                        $consignment=$data->consignment_id;
                        $tracking=$data->tracking_code;

                        $show = "
                        <button class='btn btn-info m-2 check-invoice' data-cinvoice='$cinvoice'>Check Invoice</button>
                        <button class='btn btn-info m-2 check-consignment' data-consignment='$consignment'>Check Consignment ID</button>
                        <button class='btn btn-info  m-2 check-tracking' data-tracking='$tracking'>Check Tracking ID</button>
                        ";
                        return $show ;
                    })


                    ->rawColumns(['order','customer','product','courier','booking','action'])
                    ->toJson();
                }

             return view('admin.report.courier_product_list');
            } catch (\Exception $exception) {
                return redirect()->back()->with('error', $exception->getMessage());
        }

        return view('admin.report.courier_product_list');
    }

    public function consignmentIdByCheck($id)
    {
       $data = $this->bookedCheck("/status_by_cid",$id);

       return $data->status ==200 ?$data->delivery_status: "Something Wrong!";

    }
    public function invoiceIdByStatusCheck($invoice)
    {
       $data = $this->bookedCheck("/status_by_invoice",$invoice);
       return $data->status ==200 ?$data->delivery_status: "Something Wrong!";

    }
    public function trackingCodeCheck($trackingCode)
    {
       $data = $this->bookedCheck("/status_by_trackingcode",$trackingCode);
       return $data->status ==200 ?$data->delivery_status: "Something Wrong!";
    }


    //courier_steadfast
    public function orderBooking(Request $request)
    {
        if(empty($request->idArray)){
            return json_encode($request->idArray);
        }
        $idArray = $request->idArray;
        $orders = OrderShipping::with('order')->whereIn('order_id',  $idArray)->get();
        // // $orders = OrderShipping::with('order')->limit(1)->get();

        $data = array();
        foreach ($orders as $order) {
            $data[] = [
                'invoice' => $order->order_id,
                'recipient_name' => $order->billing_first_name ? @$order->billing_first_name. ' '.@$order->billing_last_name   : 'N/A',
                'recipient_address' => $order->shipping_address_1 ? @$order->shipping_address_1: 'N/A',
                'recipient_phone' => $order->shipping_phone_number ? @$order->shipping_phone_number : '',
                'cod_amount' => @$order->order->price_total/100,
                'note' => "",
            ];
        }
       $result=  $this->bulkBook(json_encode($data));

       if($result->status == 200){
        if(isset($result->data)){
            if(is_array($result->data)){
                foreach ($result->data as $key => $booking) {
                    $findC=Order::find($booking->invoice);
                    $findC->courier_status=1;
                    $findC->save();
                    $courierBooking = new CourierBooking();
                    $courierBooking->courier_id = @$findC->courier_service;
                    $courierBooking->order_id =$booking->invoice;
                    $courierBooking->invoice =$booking->invoice;
                    $courierBooking->consignment_id =$booking->consignment_id;
                    $courierBooking->tracking_code =$booking->tracking_code;
                    $courierBooking->details =json_encode($booking);
                    $courierBooking->status =$booking->status;
                    $courierBooking->save();
                }
            }
        }
       }

         return $result->message;
        //  return $result->data;
    }

    //courier_steadfast
    public function bulkBook($data)
    {
        $courier = Courier::where('key','courier_steadfast')->first();
        $api_key = $courier->api_key;
        $secret_key = $courier->secret_key;
        $base_url = $courier->api_url;
        $response = Http::withHeaders([
            'Api-Key' => $api_key,
            'Secret-Key' => $secret_key,
            'Content-Type' => 'application/json'
        ])->post($base_url.'/create_order/bulk-order', ['data' =>$data,]);
        return json_decode($response->getBody()->getContents());
    }

    //courier_steadfast
    public function bookedCheck($link,$any)
    {
        $courier = Courier::where('key','courier_steadfast')->first();
        $api_key = $courier->api_key;
        $secret_key = $courier->secret_key;
        $base_url = $courier->api_url;
        $response = Http::withHeaders([
            'Api-Key' => $api_key,
            'Secret-Key' => $secret_key,
            'Content-Type' => 'application/json'
        ])->get($base_url.$link.'/'.$any);
        return json_decode($response->getBody()->getContents());
    }




}
