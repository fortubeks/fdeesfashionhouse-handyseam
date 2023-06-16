@extends('layouts.app', ['activePage' => 'table', 'titlePage' => __('Create Order')])

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Create Invoice') }}</div>

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
                    <form action="{{ url('orders/') }}" method="post">
                    @csrf
                        <div class="form-group">
                            <input type="text" readonly class="form-control" value="{{ __($customer->name ?? 'None') }}" aria-label="Enter SKU No">
                            <input type="hidden" name="customer_id" class="form-control" value="{{ __($customer->id ?? 'None') }}" >  
                        </div>
                        
                        <div class="form-group">
                        <textarea name="order_style" readonly rows="4" class="form-control" >{{ __(session('order_style') ?? 'None') }}</textarea>  
                        </div>
                        <div class="form-group">
                        <input type="text" name="total_amount" step=".001" min="0" class="form-control" value="" placeholder="Enter Total Amount"> 
                        </div>
                        <div class="form-group">
                        <input type="date" name="expected_delivery_date" class="form-control" value="" > 
                        </div>
                        <input type="hidden" name="order_type" class="form-control" value="{{ __('tailoring') }}" >

                        <button type="submit" class="btn btn-outline-success">Create Order</button>  
                        </div>
                    </form>
                </div>

            </div>
            
            
            
        </div>
    </div>
</div>
@endsection
