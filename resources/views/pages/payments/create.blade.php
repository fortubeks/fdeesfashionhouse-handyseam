@extends('layouts.app', ['activePage' => 'payments', 'titlePage' => __('Payments')])
@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card ">
          <div class="card-header card-header-primary">
            <h4 class="card-title mt-0">{{ __('Add New Payment To Invoice') }} <button style="float:right" type="button" id="btn_print_invoice" class="btn btn-primary">Print Invoice</button></h4>
            <p class="card-category"> </p>
          </div>
            
          <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="card ">
                        <form action="{{ url('payments/') }}" method="post">
                            @csrf
                            <div class="card-body ">
                                <div class="form-group bmd-form-group mb-4">
                                    <input type="text" readonly class="form-control" value="{{__('Invoice #'.$invoice->id)}}" >
                                    <input type="hidden" id="invoice_id" name="invoice_id" readonly class="form-control" value="{{__($invoice->id)}}" > 
                                </div>
                                <div class="form-group bmd-form-group mb-4">
                                    <input type="number" required autofocus step=".001" min="0" name="amount" class="form-control" placeholder="Enter Amount Paid" >
                                </div>
                                <div class="form-group bmd-form-group mb-4">
                                    <label>Mode of Payment</label>
                                    <select class="form-select form-control" name="mode_of_payment" id="mode_of_payment">
                                        <option value="transfer">Transfer</option>    
                                        <option value="pos">POS</option>
                                        <option value="cash">Cash</option>
                                    </select> 
                                </div> 
                                <div class="form-group bmd-form-group mb-4">
                                <textarea name="notes" rows="1" class="form-control" placeholder="Enter details of payment like POS receipt number" ></textarea>
                                </div>
                            </div>
                            <div class="card-footer ">
                                <div class="col-md-3">
                                    <a href="javascript:history.back()">Cancel & Go Back</a> 
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary">Add Payment</button>
                                </div>
                                  
                            </div>
                        </form>
                    </div>
                </div>
                
            </div>
            
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

<script>
window.addEventListener('load', function() {
  $("#btn_print_invoice").on("click", function(){
      
      var id = $('#invoice_id').val();
      var url = "{{ url('/payments/printPDFInvoice') }}/"+id;

      window.open(url , '_blank');                           
      
  });
});
</script>
