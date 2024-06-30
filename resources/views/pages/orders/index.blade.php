@extends('layouts.app', ['activePage' => 'orders', 'titlePage' => __('Orders')])

@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card card-plain mt-0">
          <div class="card-header">
            <div class="row">
              <div class="col-md-2">
              <a href="{{ url('orders/create') }}" class="btn btn-primary" >Add New Order</a>
              </div>
              <div class="col-md-2"> 
                
              </div>
              <div class="col-md-8">
                <form action="{{ url('/orders-search-by-customer') }}" method="get">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <div class="input-group-text"><i class="material-icons">face</i></div>
                        </div>
                        <input type="text" name="search_value" class="form-control" placeholder="Enter customer name or phone number to search for customer" >
                      </div>
                    </div>
                    <div class="col-md-3">

                      <select class="form-select" name="search_by" aria-label=".form-select-sm example">
                        <option selected value="name">Search By Name</option>
                        <option value="phone">Search By Phone</option>
                      </select>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-primary" type="sumbit" id="">Search</button>
                    </div>  
                  </div>
                </form>
              </div>
              
            </div>
            <div class="row">
              <div class="col-md-4">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">FITTING DATE  FROM</span>
                  </div>
                  <input name="from_filter" class="form-control" @if(isset($from)) value="{{$from}}" @else value="{{date('Y-m-d')}}" @endif type="date" required>
                </div>
              </div>
              <div class="col-md-3">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">TO</span>
                  </div>
                  <input name="to_filter" class="form-control" @if(isset($to)) value="{{$to}}" @else value="{{date('Y-m-d')}}" @endif type="date" required>
                </div>
              </div>
              <div class="col-md-2">
                <button class="btn btn-primary" type="submit">Filter</button>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover">
                <thead class="">
                    <th>Order Date</th>
                    <th>Customer Name</th>
                    <th>Expected Date For Fitting</th>
                    <th>Amount</th>
                    <th>Status</th>
                </thead>
                <tbody>
                  @if(count($orders)<=0)
                    <tr><td>No Orders found</td></tr>
                    @endif
                  @foreach($orders ?? [] as $order)
                  <tr class="item" data-id="{{__($order->id)}}">
                  <td>
                  @if($order->created_at)
                  {{ __( $order->created_at->format('d-M-y h:i:s') ?? 'None') }}
                  @else
                  {{ __( $order->updated_at->format('d-M-y h:i:s') ?? 'None') }}
                  @endif
                  </td>
                  <td>
                      {{ __($order->customer->name) }}
                  </td>
                  <td>
                    <?php $exp_date = date('d-M-Y', strtotime($order->expected_delivery_date));?>
                  {{ __($exp_date) }}
                  </td>
                  <td>
                      {{ __(formatCurrency($order->total_amount)) }}
                  </td>
                  <td>
                  {{ __($order->status ?? 'None') }}
                  </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
              @if($orders instanceof \Illuminate\Pagination\LengthAwarePaginator )
                <div class="justify-content-center">{{$orders->appends(request()->query())->links()}}</div>
                @endif
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
    $('.item').click(function() {
    var id = $(this).attr("data-id");
    var url = "{{ url('orders/') }}/"+id;
    if(id) {
        window.location = url;
    }
})
});
</script>