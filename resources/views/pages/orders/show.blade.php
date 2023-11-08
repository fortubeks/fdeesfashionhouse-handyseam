@extends('layouts.app', ['activePage' => 'orders', 'titlePage' => __('Order Details')])
<style>
    .vertical-align {
    display: flex;
    justify-content: center;
    align-content: center;
    flex-direction: column;
}
</style>
@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 text-right">
            <form id="delete_form" onsubmit="return confirm('Are you sure you want to delete this order & all its associating payments?');" style="display: inline;float: right;margin-bottom: 0px;" action="{{ url('orders/'.$order->id) }}" method="post">
                @csrf
                <input type="hidden" name="_method" value="DELETE">
            </form>
            <button type="button" data-invoice-id="{{__($order->id ?? '') }}" id="btn_print_invoice" class="btn btn-sm btn-primary">Print Thermal Receipt</button>
            <button type="button" data-invoice-id="{{__($order->invoice->id ?? '') }}" id="btn_print_pdf_receipt" class="btn btn-sm btn-primary">Print PDF Receipt</button>
            <button type="button" data-invoice-id="{{__($order->invoice->id ?? '') }}" id="btn_print_pdf_invoice" class="btn btn-sm btn-primary">Print PDF Invoice</button>
            <button id="btn_delete" class="btn btn-sm btn-secondary">Delete Order</button>
            <a href="{{ url('orders/') }}" class="btn btn-sm btn-primary">Back to list<div class="ripple-container"></div></a>
            </div>
        </div><form action="{{ url('orders/'.$order->id) }}" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
            @csrf
        <div class="row">
        
            <div class="col-md-6"> 
            
                <div class="row">
                    <div class="col-md-12">
                       
                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <label for="" class="">Customer Name</label> 
                                <p><a class="btn btn-secondary" href="{{ url('customers/'.$order->customer->id) }}">{{ __($order->customer->name ?? 'None') }}</a> </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="" class="">Date/Time Created</label>
                                <input type="text" onfocus="(this.type='date')" name="created_at" style="width: 70%;" value="{{ __($order->created_at->format('d-m-Y') ?? 'None') }}" >
                            </div>
                        </div>
                        <div class="form-row">  
                            <div class="col-md-6 mb-3">
                                <label for="" class="">Expected Date for Fitting</label>
                                <input type="text" onfocus="(this.type='date')" name="expected_delivery_date" style="width: 50%;" value="{{ __($order->expected_delivery_date ?? 'None') }}" >
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="" class="">Total Amount</label>
                                <input type="number" name="total_amount" readonly class="form-control" value="{{ __($order->total_amount ?? 'None') }}" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="">Status</label>
                            <select class="form-select" data-status="{{ __($order->status ?? 'None') }}" name="status" id="status">
                                <option value="Pending Payment">Pending Payment</option>     
                                <option value="Processing">Processing</option>
                                <option value="Delivered">Delivered</option>
                            </select> 
                        </div>
                        
                    </div>
                    <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card card-plain">
                                    <div class="card-header card-header-primary">
                                        <h4 class="card-title mt-0"> Payments</h4>
                                        <p class="card-category"> </p>
                                    </div>
                                    <div class="card-body">
                                        @if($order->invoice)
                                        @if(count($order->invoice->payments)>0)
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead class="">
                                                <th>
                                                    Payment Date
                                                </th>
                                                <th>
                                                    Mode of Payment
                                                </th>
                                                <th>
                                                    Amount
                                                </th>
                                                <th>
                                                    Action
                                                </th>
                                                </thead>
                                                <tbody>
                                                
                                                @foreach($order->invoice->payments ?? [] as $payment)
                                                
                                                <tr class="item">
                                                    <td>
                                                    {{ __( $payment->created_at->format('d-m-Y h:i:s') ?? 'None') }}
                                                    </td>
                                                    <td>
                                                        {{ __($payment->mode_of_payment) }}
                                                    </td>
                                                    <td>
                                                    {{ __($payment->amount ?? 'None') }}
                                                    </td>
                                                    <td>
                                                    <form onsubmit="return confirm('Are you sure you want to delete this payment?');" style="display: inline;float: right;margin-bottom: 0px;" action="{{ url('payments/'.$payment->id) }}" method="post">
                                                            @csrf
                                                            <input type="hidden" name="_method" value="DELETE">
                                                            <button type="submit" class="btn btn-link">Delete</button> 
                                                        </form> 
                                                    </td>
                                                </tr>
                                                
                                                @endforeach
                                                <tr>
                                                    <td><b>Outstanding Balance: {{ __(formatCurrency($order->invoice->getOutstandingBalance())) }}</b></td>
                                                </tr>
                                                </tbody> 
                                            </table>                       
                                                
                                        </div>
                                        @else
                                        <div class="">
                                            <p>No Payments yet. Resend invoice to customer</p>
                                        </div>
                                        @endif
                                        <div class="">
                                            <p><a href="{{ url('payments/create?order_id='.$order->id) }}" class="btn btn-primary" >Add New Payment</a></p>
                                        </div>
                                        @endif
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            
            <div class="col-md-6">
            
                @if($order->order_type == 'tailoring')
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="" class="">Instruction For Outfits </label>
                        <textarea name="instructions" rows="2" class="form-control" >{{ __($order->instructions ?? 'None') }}</textarea>    
                    </div>
                </div>
                @foreach($order->outfits as $outfit)
                <div class="row img-thumbnail mb-3" style="padding:20px;">
                    <div class="col-md-8 vertical-align">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-control"> <?php if($outfit->customer){echo $outfit->customer->name;} else{echo 'Customer:';} ?> </label>
                            </div>
                            <div class="col-md-8">
                                <div class="mt-2 mb-2">
                                    <input type="hidden" id="customer_id" name="customer_id[]">
                                    <input oninput="setCustomerID()" class="form-control" list="customerdatalistOptions" id="customer" placeholder="Type to add customer (optional)">
                                    <datalist id="customerdatalistOptions">
                                        @foreach(getModelList('customers') as $customer)
                                        <option value="{{$customer->name}}" data-value="{{$customer->id}}">
                                        @endforeach
                                    </datalist>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-control"> Outfit </label>
                            </div>
                            <div class="col-md-8">
                                <input value="{{$outfit->name}}" type="text" name="name[]" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-control"> Price </label>
                            </div>
                            <div class="col-md-8">
                                <input value="{{$outfit->price}}" step=".001" min="0" type="number" name="price[]" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-control"> Quantity </label>
                            </div>
                            <div class="col-md-8">
                                <input value="{{$outfit->qty}}" type="number" name="qty[]" class="form-control">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-control"> Tailor: </label>
                            </div>
                            <div class="col-md-8">
                                <select name="staff_id[]" class="form-select mb-3 tailor" data-tailor="{{ __($outfit->staff_id ?? '') }}">
                                    <option value="" selected>Select Tailor To Assign Job To</option>
                                    @php
                                    $active_tailors = auth()->user()->active_tailors();
                                    @endphp
                                    @foreach ($active_tailors as $tailor)
                                    <option {{ (($outfit->staff_id == $tailor->id))  ? 'selected' : ''}} value="{{ __($tailor->id) }}">{{ __($tailor->getFullName()) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        

                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-control">Image</label>
                            </div>
                            <div class="col-md-8">
                            <input type="file" name="styles[]" class="form-control mb-2">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-control"> Instruction: </label>
                            </div>
                            <div class="col-md-8">
                                <textarea rows="1" class="form-control" name="instruction[]">{{$outfit->instruction}}</textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-control">Items Used</label>
                            </div>
                            <div class="col-md-8">
                                <input placeholder="Add" type="text" onclick="setOutfitId(this)" data-outfit="{{$outfit->id}}" data-toggle="modal" data-target="#loginModal" name="" class="form-control">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-control">Amount To Pay Tailor</label>
                            </div>
                            <div class="col-md-6">
                                <input type="number" step=".001" min="0" value="{{ __($outfit->tailor_cost ?? '') }}" placeholder="" name="tailor_cost[]" class="form-control">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-8">
                                <label class="form-control">Cost For Material & Appliques</label>
                            </div>
                            <div class="col-md-4">
                                <input type="number" step=".001" min="0" value="{{ __($outfit->material_cost ?? '') }}" placeholder="" name="material_cost[]" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="" class="form-control">Status: </label>
                            </div>
                            <div class="col-md-8">
                        
                                <select class="form-select job-status" data-status="{{ __($outfit->job_status ?? 'None') }}" name="job_status[]">
                                    <option value="Cutting">Cutting</option>
                                    <option value="Sewing">Sewing</option>
                                    <option value="Ready For Trial">Ready For Trial</option>
                                    <option value="Ready">Ready</option>
                                    <option value="Delivered">Delivered</option>
                                </select> 
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-4">
                            <button type="button" data-outfit-id="{{__($outfit->id ?? '') }}" id="btn_print_outfit_inst" class="btn btn-sm btn-primary btn_print_m_ins">Print Measurement & Instruction</button>
                            </div>
                        </div>
                        
                        <input type="hidden" name="outfit[]" value="{{ __($outfit->id ?? '') }}">
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-12">
                                <?php $url =  asset('/storage/styles/'.$outfit->image); ?>
                                <a href="{{ $url }}" target="_blank" onclick="window.open('{{ $url }}', 'popup'); return false;">
                                <img class="img-thumbnail" width="200px" style="margin-right: 20px;" src="{{ $url }}"/> </a>
                            </div>
                            <div class="col-md-12">
                                <p><label class="form-control">Items Used ({{formatCurrency($outfit->items_used()->sum('amount'))}})</label></p>
                                @forelse($outfit->items_used as $item_used)
                                <p>{{$item_used->item->description}}({{$item_used->qty}})</p>
                                @empty
                                None
                                @endforelse
                            </div>
                        </div>
                        
                    </div>
                </div>
                
                @endforeach
                <div class="row">
                    <div class="col-md-12">
                        <div class="row" id="stylesForUploadContainer"></div>
                        <div class="row">
                            <div class="increment" ></div>
                            <div class="input-group control-group " >
                                
                                <div class="input-group-btn mt-3"> 
                                    <button style="margin: 0 auto;" data-id="new_entry" class="btn btn-success" type="button"><i class="glyphicon glyphicon-plus"></i>Add Another Outfit</button>
                                </div>
                            </div>
                            <button style="display:none; margin: 0 auto;" type="button" onclick="createNewFileInput()" class="btn btn-primary">Add Another</button>
                        </div>
                    </div>
                </div>
                
                @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="">
                        <th>
                        {{ __( 'Order Items')}}
                        </th>
                        <th>
                        {{ __( 'Amount')}}
                        </th>
                        </thead>
                        <tbody>
                        @if(count($order->order_items)>0)
                        @foreach($order->order_items ?? [] as $order_item)
                        
                        <tr class="item">
                            <td>
                            {{ __( $order_item->item->description ?? 'None') }}
                            </td>
                            <td>
                            {{ __( $order_item->amount ?? 'None') }}
                            </td>
                        </tr>
                        
                        @endforeach
                        </tbody> 
                    </table>
                
                    
                    @else
                        <div><p>No items found</p></div>
                    @endif                       
                        
                </div>    
                @endif
            
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-sm-6 mx-auto" style="text-align:center">
                <input type="hidden" name="order_type" class="form-control" value="{{ __($order->order_type ?? 'None') }}" >
                <div class="form-group">
                    <input type="hidden" name="_method" value="PUT">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </div>
        </form>
    </div>
</div>
@endsection
<div class="modal fade" id="loginModal" tabindex="-1" role="">
    <div class="modal-dialog modal-login" role="document">
        <div class="modal-content">
            <div class="card card-signup card-plain">
                <div class="modal-header">
                  <div class="card-header card-header-primary text-center" style="width: 100%;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                      <i class="material-icons">clear</i>
                    </button>
                    <h4 class="card-title">Add Items</h4> 
                  </div>
                </div>
                <div class="modal-body">
                    <form class="form" method="post" action="{{ url('add-items-used/') }}">
                        @csrf
                        <p class="description text-center">Add Items Used For Making This Order</p>
                        <div class="card-body">

                            <div class="form-group bmd-form-group mb-4">
                              <select required class="form-select" onchange="setCost(this)" name="item_id" id="item">
                                <option value="">--Select--</option>
                                  @foreach (getModelList('inventory') as $item)
                                  <option data-cost="{{ __($item->cost_price) }}" value="{{ __($item->id) }}">{{ __($item->description) }}</option>
                                  @endforeach
                              </select>
                            </div>

                            <div class="form-group bmd-form-group mb-4">
                              <label>Quantity</label>
                              <input type="number" required name="qty" id="qty" onkeyup="updateTotal()" inputmode="decimal" min="0" step="any" class="form-control" placeholder="Enter Quantity" >
                            </div>

                            <div class="form-group bmd-form-group mb-4">
                              <label >Unit Cost</label>
                              <input type="number" required name="unit_cost" onkeyup="updateTotal()" inputmode="decimal" min="0" step="any" id="unit_cost" class="form-control" placeholder="Enter Unit Cost" >
                            </div>

                            <div class="form-group bmd-form-group mb-4">
                              <label>Amount</label>
                              <input type="number" required name="amount" id="amount" class="form-control" readonly >
                            </div>
                        </div>
                    
                </div>
                <div class="modal-footer justify-content-center">
                    <input type="hidden" name="outfit_order_id" id="outfit_order_id">
                    <button type="submit" class="btn btn-primary btn-link btn-wd btn-lg">Add</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<style>
.form-control {
    font-size: 1.063rem !important;
}
.form-group input[type=file] {
    opacity: 1 !important;
    position: relative !important;
    width: 100% !important;
    z-index: 0 !important;
}
</style>
<script>
window.addEventListener('load', function() {
    $('.item').click(function() {
    var id = $(this).attr("data-id");
    var url = "{{ url('orders/') }}/"+id;
    if(id) {
        window.location = url;
    }
})
$("#btn_print_pdf_invoice").on("click", function(){
                                
    var id = $(this).attr("data-invoice-id");
    var url = "{{ url('/payments/printPDFInvoice') }}/"+id;

    window.open(url , '_blank');                           
    
});

$("#btn_print_invoice").on("click", function(){
                                
    var id = $(this).attr("data-invoice-id");
    var url = "{{ url('/payments/printThermalInvoice') }}/"+id;

    window.open(url , '_blank');                           
    
});

$(".btn_print_m_ins").on("click", function(){
                                
    var id = $(this).attr("data-outfit-id");
    var url = "{{ url('/printMeasurementInst') }}/"+id;

    window.open(url , '_blank');                           
    
});

$("#btn_print_pdf_receipt").on("click", function(){
                                
    var id = $(this).attr("data-invoice-id");
    var url = "{{ url('/payments/printPDFReceipt') }}/"+id;

    window.open(url , '_blank');                           
    
});

$("#btn_delete").on("click", function(){
    $('#delete_form').submit();

});

var status = $('#status').attr("data-status");
$('#status option[value="'+status+'"]').attr('selected','selected');

$(".job-status").each(function(){
    var job_status = $(this).attr("data-status");
    $(this).find('option').each(function() {
      console.log(job_status + 'hhh' + $(this).val());
      if($(this).text() == job_status){
            $(this).attr('selected','selected');
        }
    });
    
});


});

function setCustomerID(){
    var value = $('#customer').val();
    $('#customer_id').val($('#customerdatalistOptions [value="' + value + '"]').data('value'));
}

function updateTotal(){
    var qty = $('#qty').val();
    var unit_cost = $('#unit_cost').val();

    var amount = qty*unit_cost;
    round_figure = parseFloat(amount.toFixed(2));
    $('#amount').val(round_figure);
}
function setCost(item){
    var unit_cost = $(item).find(':selected').data('cost');
    $('#unit_cost').val(unit_cost);
    updateTotal();
}
function setOutfitId(outfit){
    var outfit_order_id = $(outfit).data('outfit');
    $('#outfit_order_id').val(outfit_order_id);
}
</script>
<script>
window.addEventListener('load', function() {
    var element_id = 0;
      $(".btn-success").click(function(){ 
          element_id++;
          var id = $(this).attr("data-id");
          //var newEntry = $("#"+id).val();
          var html = '<div id="'+element_id+'" class="" style="margin-top:10px;border:1px solid grey;padding:15px;" >';
              html += '<p><input required placeholder="Write the name of the style" type="text" name="name[]" class="form-control mb-2"></p>';
              html += '<p><input required placeholder="Price charged" step=".001" min="0" type="number" name="price[]" class="form-control mb-2"></p>';
              html += '<p><input  placeholder="Quantity (optional)" type="number" name="qty[]" class="form-control"></p>';
              html += '<p><textarea class="form-control" placeholder="Instruction (optional)" name="instruction[]"></textarea></p>';
              html += '<input type="hidden" name="outfit[]" >';
              html += '<p><input type="file" name="styles[]" class="form-control mb-2"><button class="mt-3 delete" data-id="'+element_id+'" type="button"><i class="material-icons">delete</i> </button></p>';
              html += '</div>';                 
          $(".increment").after(html);
      });

      $("body").on("click",".delete",function(){
        var id = $(this).attr("data-id");
        $("#"+id).remove();
          //$(this).parents(".control-group").remove();
      });
});
</script>