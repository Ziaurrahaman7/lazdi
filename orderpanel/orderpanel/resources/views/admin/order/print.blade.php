<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
</head>
<style>
    header{
        background: #ededed;
    }
    .t-left{
        text-align: left;
    }
    .t-center{
        text-align: center;
    }
    .t-right{
        text-align: right;
    }
    .w-25{
        width:25%;
    }
    .d-flex{
        display:flex;
    }
    p{
        margin:1px;
        line-height: 25px;
    }
    .px-30{
        padding-left:30px;
        padding-right:30px;
    }
    .b3{
        border-top:3px solid #ededed;
    }
    .border{
        border-collapse: collapse;
        border:1px solid #00000078;
        margin-top: 30px;
    }
    .border th{
        border:1px solid #00000078;
    }
    .border td{
        border:1px solid #00000078;
        padding: 3px;
    }
</style>
<body>
   <div class="content">
    <header class="px-30">
        <table style="width: 100%;">
            <tr>
                <th class="t-left w-25"><img style="height:50px; width:180px;" src="/{{$logo->logo}}" alt=""></th>
                <th class="t-center"><h1 style="margin: 0px;">Invoice</h1></th>
                <th class="t-right w-25">
                    <p>{{ date('d F, Y') }}</p>
                    <p>INVO-{{ $order->order_number }}</p>
                </th>
            </tr>
        </table>
    </header>
        <table class="px-30" style="width: 100%;padding-top:10px;">
            <tr>
                <th  class="t-left"><h3 style="margin: 0px;">Billing Information</h3></th>
                <th style="text-align: right"  class="t-left"><h3 style="margin: 0px;">Shipping Information</h3></th>
            </tr>
            <tr>
                <td>
                    @if ($siteInfo->contact_address)
                    <p>{{$siteInfo->contact_address}}</p>
                    @endif

                    @if ($siteInfo->contact_email)
                    <p>Email: {{$siteInfo->contact_email}}</p>
                    @endif
                    @if ($siteInfo->contact_phone)
                    <p>Phone: {{$siteInfo->contact_phone}}</p>
                    @endif
                </td>
                <td  style="text-align: right">
                    <div>
                        <p><strong>Name:</strong> {{ $customer->billing_first_name ?? '--' }}</p>
                        <p><strong>Address:</strong> {{ $customer->shipping_address_1 ?? '--'}}</p>
                        <p><strong>Phone:</strong> {{ $customer->shipping_phone_number ?? '--'}}</p>
                    </div>
                </td>
            </tr>
        </table>
        <table class="px-30 t-center b3 border" style="width: 100%;padding-top: 20px;">
            <tr>
                <th>Title</th>
                <th class="t-center">Image</th>
                <th class="t-center">Quantity</th>
                <th class="t-center">Unit Price</th>
                <th class="t-center">Total</th>
            </tr>

            @foreach($products as $key => $product)
            <tr>
                <td class="t-left">{{ $product->title ?? '-'}}</td>
                <td><img class="img-responsive" width="50" src="{{ env('IMAGE_PATH') . $product->image_default }}"></td>
                <td>{{ $product->product_quantity }}</td>
                <td>{{ $product->product_unit_price/100 }}</td>
                <td>{{ $product->product_unit_price/100 * $product->product_quantity }}</td>
            </tr>
            @endforeach

            <tr>
                <td class="t-right" colspan="4">Delivari Cost</td>
                <td><strong>{{ $customer->delivery_cost}}</strong></td>
            </tr>
            <tr>
                <td class="t-right" colspan="4">Discount Price</td>
                <td><strong>{{ $order->customDiscount/100 }}</strong></td>
            </tr>
            <tr>
                <td class="t-right" colspan="4">Advance Price</td>
                <td><strong>{{ $order->advance_price/100 }}</strong></td>
            </tr>
            <tr>
                <td class="t-right" colspan="4">Total</td>
                <td><strong>৳ {{ $order->price_total/100 }}</strong></td>
            </tr>
        </table>
   </div>
</body>

<script type="text/javascript">
    localStorage.clear();
    function auto_print() {
        window.print()
    }
    setTimeout(auto_print, 1000);
</script>
</html>
