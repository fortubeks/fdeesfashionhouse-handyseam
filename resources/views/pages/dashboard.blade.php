@extends('layouts.admin')
<style>
.order{
    cursor:hand; 
    background: #fff;
}
.order:hover {background: #f5f5f5;}
</style>
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="form-row">
                        <div class="col-md-4 ">
                            <p><a class="btn btn-primary btn-lg btn-block" href="{{ url('orders/') }}"> <i class="bi bi-cart4"></i> Sales & Orders</a></p></div>
                        <div class="col-md-4 ">
                            <p><a class="btn btn-primary btn-lg btn-block" href="{{ url('customers/') }}"> <i class="bi bi-people"></i> Customers</a></p>
                        </div>
                        <div class="col-md-4">
                            <p><a class="btn btn-primary btn-lg btn-block" href="{{ url('items/') }}"> <i class="bi bi-handbag"></i> Items & Inventory</a></p>
                        </div>
                    </div>
                    
                </div>
            </div>
            <div class="card">
                <div class="card-header">{{ __('Orders For Delivery This Week') }}</div>

                <div class="card-body">

                <div class="row table" style="background-color: rgba(39,41,43,0.03);">
                        <div class="col" style="border: 1px solid rgba(39,41,43,0.1);">
                            {{ __('Order Date') }}
                        </div>
                        <div class="col" style="border: 1px solid rgba(39,41,43,0.1);">
                            {{ __('Customer Name') }}
                        </div>
                        <div class="col" style="border: 1px solid rgba(39,41,43,0.1);">
                            {{ __('Expected Date of Delivery') }}
                        </div>
                        <div class="col" style="border: 1px solid rgba(39,41,43,0.1);">
                            {{ __('Status') }}
                        </div>
                    </div>
                    @if(count($orders)>0)
                    @foreach($orders ?? [] as $order)
                        @if($order->order_type == "tailoring" )
                            <div class="row order table table-hover" data-id="{{$order->id}}" style="">
                            <div class="col" style="border: 1px solid rgba(39,41,43,0.1);">
                                    {{ __( $order->created_at->diffForHumans() ?? 'None') }}
                                </div>
                                <div class="col" style="border: 1px solid rgba(39,41,43,0.1);">
                                <?php $customer = App\Models\Customer::find($order->customer_id); ?>
                                {{ __($customer->name) }}
                                </div>
                                <div class="col" style="border: 1px solid rgba(39,41,43,0.1);">
                                    {{ __($order->expected_delivery_date ?? 'None') }}
                                </div>
                                <div class="col" style="border: 1px solid rgba(39,41,43,0.1);">
                                {{ __($order->status ?? 'None') }}
                                </div>
                            </div>
                        @endif
                        @endforeach
                        
                    @else
                        <p>No Orders found</p>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
<script>
window.addEventListener('load', function() {
    $(".order").on("click", function(){
        
        var id = $(this).attr("data-id");
        var url = "{{ url('orders/') }}/"+id;

        window.location = url;                           
        
    });
})
</script>