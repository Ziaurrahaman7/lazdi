<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class AdminController extends Controller
{
    public function index()
    {
                 // $new = DB::table('orders')->where('status', 1)->where('price_total', '>', 0)->count();
                $new = DB::table('orders')
                ->join('order_shipping', 'orders.id', '=', 'order_shipping.order_id')
                ->where('orders.status', 1)
                ->where('orders.price_total', '>', 0)
                ->count();

                $pendingPayment = DB::table('orders')
                ->join('order_shipping', 'orders.id', '=', 'order_shipping.order_id')
                ->where('orders.status', 2)
                ->count();
                
                $pending = DB::table('orders')
                ->join('order_shipping', 'orders.id', '=', 'order_shipping.order_id')
                ->where('orders.status', 3)
                ->count();
                $courier = DB::table('orders')
                ->join('order_shipping', 'orders.id', '=', 'order_shipping.order_id')
                ->where('orders.status', 4)
                ->count();
                $invoice = DB::table('orders')
                ->join('order_shipping', 'orders.id', '=', 'order_shipping.order_id')
                ->where('orders.status', 5)
                ->count();
                $pendingInvoice = DB::table('orders')
                ->join('order_shipping', 'orders.id', '=', 'order_shipping.order_id')
                ->where('orders.status', 6)
                ->count();
                $delivered = DB::table('orders')
                ->join('order_shipping', 'orders.id', '=', 'order_shipping.order_id')
                ->where('orders.status', 7)
                ->count();
                $booking = DB::table('orders')
                ->join('order_shipping', 'orders.id', '=', 'order_shipping.order_id')
                ->where('orders.status', 8)
                ->count();
                $return = DB::table('orders')
                ->join('order_shipping', 'orders.id', '=', 'order_shipping.order_id')
                ->where('orders.status', 9)
                ->count();
                $cancelled = DB::table('orders')
                ->join('order_shipping', 'orders.id', '=', 'order_shipping.order_id')
                ->where('orders.status', 10)
                ->count();
        return view('admin.include.main', compact('new','pendingPayment','pending','courier','invoice','pendingInvoice','delivered','booking','return','cancelled'));
    }
}
