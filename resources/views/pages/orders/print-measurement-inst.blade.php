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
            <h2 style="line-height: 1.2;">{{__(auth()->user()->user_account->app_settings->business_name)}}</h2>
            
            <p> {{__(auth()->user()->user_account->app_settings->business_address)}}
            </p>
        </div>
        <p>{{__('Order Date')}}: {{$order->created_at->format("d-M-Y h:i:s")}}<br>
            
            {{__('Customer')}}: {{(($outfit->customer)) ? $outfit->customer->name : $order->customer->name}}<br>
            {{__('Style')}}: {{$outfit->name}}<br>
            {{__('Assigned Tailor')}}: {{(($outfit->tailor)) ? $outfit->tailor->getFullName() : ''}}<br>
            {{__('Fitting Date')}}: {{$order->expected_delivery_date}}
        </p>
        <table class="table-data">
            <tbody>
                @php
                    $customer_measurement = $order->customer->measurement_details;
                    if($outfit->customer){
                    $customer_measurement = $outfit->customer->measurement_details;
                    }
                  $customer_measurement_details = json_decode($customer_measurement, true);
                @endphp
                @foreach($customer_measurement_details as $key => $measurement_detail)
                    <tr>
                        <td colspan="4" style="text-align:left">{{ __($key) }}</td>
                        <td style="text-align:right">{{ __($measurement_detail) }}</td>
                    </tr>
                                        
                @endforeach
                

                <tr style="background-color:#ddd;">
                    <td class="centered" colspan="5">{{__('Instruction')}}</td>
                </tr>                
                <tr><td class="centered" colspan="5">{{__($outfit->instruction)}}</td></tr>

                
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
