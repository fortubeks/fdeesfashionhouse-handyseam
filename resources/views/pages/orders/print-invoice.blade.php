<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
   
    <title>Print</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">

    <style type="text/css">
        * {
            font-size: 16px;
            line-height: 24px;
            font-family: 'Ubuntu', sans-serif;
            text-__form: capitalize;
        }
        h2{
            font-size: 30px;
            margin-top: 0px;
        }
        .btn {
            padding: 7px 10px;
            text-decoration: none;
            border: none;
            display: block;
            text-align: center;
            margin: 7px;
            cursor:pointer;
        }

        .btn-info {
            background-color: #999;
            color: #FFF;
        }

        .btn-primary {
            background-color: #6449e7;
            color: #FFF;
            width: 100%;
        }
        td,
        th,
        tr,
        table {
            border-collapse: collapse;
        }
        tr {border-bottom: 1px dotted #ddd;}
        td,th {padding: 7px 0;width: 70%;}

        table {width: 100%;}
        tfoot tr th:first-child {text-align: left;}

        .centered {
            text-align: center;
            align-content: center;
        }
        small{font-size:11px;}

        @media print {
            * {
                font-size:12px;
                line-height: 20px;
            }
            td,th {padding: 5px 0;}
            .hidden-print {
                display: none !important;
            }
            @page { margin: 1.5cm 0.5cm 0.5cm; }
            @page:first { margin-top: 0.5cm; }
            tbody::after {
                content: ''; display: block;
                page-break-after: always;
                page-break-inside: avoid;
                page-break-before: avoid;        
            }
        }
    </style>
  </head>
<body>

<div style="max-width:400px;margin:0 auto">
    @if(preg_match('~[0-9]~', url()->previous()))
        @php $url = url()->previous(); @endphp
    @else
        @php $url = url()->previous(); @endphp
    @endif
    @php $url = url('orders/');  @endphp
    <div class="hidden-print">
        <table>
            <tr>
                <td><a href="{{$url}}" class="btn btn-info"><i class="fa fa-arrow-left"></i> {{__('Back')}}</a> </td>
                <td><button onclick="window.print();" class="btn btn-primary"><i class="dripicons-print"></i> {{__('Print')}}</button></td>
            </tr>
        </table>
        <br>
    </div>
        
    <div id="receipt-data">
        <div class="centered">
             <img src="{{ asset('/storage/logo_images/'.auth()->user()->user_account->app_settings->business_logo) }}" height="100" width="150" style="margin:10px 0;filter: brightness(0);">
            
            <h2 style="line-height: 1.2;">{{__(auth()->user()->user_account->app_settings->business_name)}}</h2>
            
            <p> {{__(auth()->user()->user_account->app_settings->business_address)}}
                <br>{{__('Phone Number')}}: {{__(auth()->user()->user_account->app_settings->business_phone)}}
            </p>
        </div>
        <p>{{__('Date')}}: {{$order->created_at->format("d-M-Y h:i:s")}}<br>
            
            {{__('Customer')}}: {{$order->customer->name}}
        </p>
        <table class="table-data">
            <tbody>
                
                @if($order->order_type == "tailoring")
                @foreach($order->outfits as $key => $outfit)
                
                <tr>
                    <td colspan="4" style="text-align:left">
                        {{__($outfit->name)}} - {{$outfit->qty}} x {{number_format((float)($outfit->price), 2, '.', ',')}}
                    </td>
                    <td style="text-align:right;vertical-align:bottom;">{{number_format((float)$outfit->getTotalAmount(), 2, '.', ',')}}</td>
                </tr>
                @endforeach
                @endif
                @if($order->order_type == "sales")
                @foreach($order->order_items as $key => $order_item)
                
                <tr>
                    <td colspan="4">
                        {{__($order_item->item->name)}}
                         - {{$order_item->qty}} x {{number_format((float)($order_item->item->price), 2, '.', ',')}}
                    </td>
                    <td style="text-align:right;vertical-align:bottom">{{number_format((float)$order_item->price, 2, '.', ',')}}</td>
                </tr>
                @endforeach
                @endif
                
                
            <!-- <tfoot> --> 
                <tr>
                    <td colspan="4" style="text-align:left">{{__('Total')}}</td>
                    <td style="text-align:right">{{number_format((float)$order->total_amount, 2, '.', ',')}}</td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align:left">{{__('VAT')}}</td>
                    <td style="text-align:right">{{number_format((float)$order->vat, 2, '.', ',')}}</td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align:left">{{__('Grand Total')}}</td>
                    <td style="text-align:right">{{number_format((float)$order->getTotalAmountPlusVAT(), 2, '.', ',')}}</td>
                </tr>

                <tr>
                    <td colspan="4" style="text-align:left">{{__('Payments')}}</td>
                    <td style="text-align:right">{{number_format((float)$order->invoice->getAmountPaid(), 2, '.', ',')}}</td>                    
                </tr>

                <tr>
                    <td colspan="4" style="text-align:left">{{__('Balance')}}</td>
                    <td style="text-align:right">{{$order->invoice->getOutstandingBalance()}}</td>                    
                </tr>

                <tr style="background-color:#ddd;">
                    <td class="centered" colspan="4">{{__('Served By')}}: {{__(auth()->user()->name)}}</td>
                </tr>                
                <tr><td class="centered" colspan="4">{{__('Thank you. Please come again')}}</td></tr>

                <tr><td class="centered" colspan="4"><div class="centered" style="margin:30px 0 50px">
                    <small>{{__('Invoice Generated By')}} {{__('HandySeam')}}.
                    {{__('Developed By')}} Fortran House</strong></small>
                    </div></td>
                </tr>
            </tbody>
            <!-- </tfoot> -->
        </table>
        
    </div>
</div>

<script type="text/javascript">
    //localStorage.clear();
    function auto_print() {     
        window.print()
    }
    setTimeout(auto_print, 1000);
</script>

</body>
</html>
