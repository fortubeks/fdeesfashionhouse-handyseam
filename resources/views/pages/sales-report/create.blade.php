@extends('layouts.app', ['activePage' => 'staff', 'titlePage' => __('Staffs')])

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Add New Payment To Invoice') }} <button style="float:right" type="button" id="btn_print_invoice" class="btn btn-primary">Print Invoice</button></div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ url('payments/') }}" method="post">
                    @csrf
                        
                        <div class="form-group">
                            <input type="text" readonly class="form-control" value="{{__('Invoice #'.$invoice->id)}}" >
                            <input type="hidden" id="invoice_id" name="invoice_id" readonly class="form-control" value="{{__($invoice->id)}}" >  
                        </div>
                        
                        <div class="form-group">
                            <input type="text" name="amount" class="form-control" placeholder="Enter Amount Paid" >  
                        </div>
                        <div class="form-group">
                            <label>Mode of Payment</label>
                            <select class="form-select form-control" name="mode_of_payment" id="mode_of_payment">
                                <option value="transfer">Transfer</option>    
                                <option value="pos">POS</option>
                                <option value="cash">Cash</option>
                            </select> 
                        </div>
                        <div class="form-group">  
                        <textarea name="notes" rows="4" class="form-control" placeholder="Enter details of payment like POS receipt number" ></textarea> 
                        </div>

                        <a href="javascript:history.back()">Cancel & Go Back</a> 
                        <button type="submit" class="btn btn-primary">Add Payment</button> 
                        
                        </div>
                    </form>
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
                                var url = "{{ url('/payments/printInvoice') }}/"+id;

                                window.open(url , '_blank');                           
                                
                            });
                            
                        })


</script>
