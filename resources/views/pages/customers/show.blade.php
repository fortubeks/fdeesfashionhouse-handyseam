@extends('layouts.app', ['activePage' => 'customers', 'titlePage' => __('Customers')])

@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
        <div class="col-md-12 mb-3">
            <form id="delete_form" onsubmit="return confirm('Are you sure you want to delete this customer?');" style="display: none;" action="{{ url('customers/'.$customer->id) }}" method="post">
                @csrf @method('DELETE')
            </form>
            <form action="{{ url('customers/'.$customer->id) }}" method="post">
                @csrf
            <div class="row mb-3">
                <div class="col-md-3">
                    <label>Customer Name</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                        <div class="input-group-text"><i class="material-icons">face</i></div>
                        </div>
                        <input type="text" name="name" class="form-control" value="{{ __($customer->name ?? 'None') }}" > 
                    </div>
                </div>
                <div class="col-md-3">
                    <label>Phone Number</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><i class="material-icons">phone</i></div>
                        </div>
                        <input type="text" name="phone" class="form-control" value="{{ __($customer->phone ?? 'None') }}" >
                    </div>
                </div>
                <div class="col-md-3">
                    <label>Email</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><i class="material-icons">email</i></div>
                        </div>
                        <input type="email" name="email" class="form-control" value="{{ __($customer->email ?? '') }}" >
                    </div>
                </div>
                <div class="col-md-3"> 
                    <label>Address</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                        <div class="input-group-text"><i class="material-icons">map</i></div>
                        </div>
                        <textarea id="address" name="address" rows="1" class="form-control" value="" placeholder="Address ...">{{ __($customer->address ?? '') }}</textarea> 
                    </div>     
                </div>  
            </div>
            <div class="row">
                <div class="col-md-6">
                  <p><a href="{{ url('measurements/'.$customer->id) }}" class="btn btn-primary ">View Measurement</a></p>
                </div>
                <div class="col-md-3 text-right">
                    <input type="hidden" name="_method" value="PUT">
                    <button type="submit" class="btn btn-primary">Update Details</button> 
                </div>
                <div class="col-md-3 text-right">
                    <button type="button" id="btn_delete" class="btn btn-primary">Delete Customer</button> 
                </div>
            </div>
            </form>
        </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card card-plain">
          <div class="card-header card-header-primary">
            <h4 class="card-title mt-0">{{ __($customer->name ?? 'None') }} Order History</h4>
            <p class="card-category"> </p>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover">
                <thead class="">
                    <th>Order Date</th>
                    <th>Number Of Items</th>
                    <th>Amount</th>
                    <th>Status</th>
                </thead>
                <tbody>
                  @if(count($customer->orders)<=0)
                    <tr><td>No Orders found</td></tr>
                    @endif
                  @foreach($orders ?? [] as $order)
                  <tr class="item" data-id="{{__($order->id)}}">
                  <td>
                  {{ __( $order->created_at->format('d-m-Y h:i:s') ?? 'None') }}
                  </td>
                  <td>
                      {{ __($order->outfits()->count()) }}
                  </td>
                  <td>
                  {{ __(formatCurrency($order->total_amount)) }}
                  </td>
                  <td>
                  {{ __($order->status) }}
                  </td>
                  </tr>
                  @endforeach
                  <tr><td colspan="2"><a class="text-success" href="{{ url('orders/create') }}">Create New Order</a></td></tr>
                </tbody>
              </table>
              @if($orders instanceof \Illuminate\Pagination\LengthAwarePaginator )
                <div class="justify-content-center">{{$orders->links()}}</div>
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

$("#btn_delete").on("click", function(){
    $('#delete_form').submit();

});

});
</script>