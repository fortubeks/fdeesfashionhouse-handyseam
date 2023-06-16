@extends('layouts.app', ['activePage' => 'report', 'titlePage' => __('Sales Report')])

@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-8 mb-3">
        <form action="{{ url('/sales-report/view') }}" method="post">
            @csrf
          
        </form>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="card card-plain">
          <div class="card-header card-header-primary">
            <h4 class="card-title mt-0">Sales/Revenue</h4>
            <p class="card-category"><label for="" class= "font-weight-bold" >Total Revenue: {{ formatCurrency($orders_sum) }}</label> </p>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover">
                <thead class="">
                    <th>Order Date</th>
                    <th>Customer</th>
                    <th>Amount</th>
                    <th>Cost</th>
                    <th>Status</th>
                </thead>
                <tbody>
                  @if(count($orders)<=0)
                    <tr><td>No Sales report found</td></tr>
                    @endif
                  @foreach($orders ?? [] as $order)
                  <tr class="item" data-id="{{__($order->id)}}">
                  <td>
                  {{ __( $order->created_at->format('d-m-Y') ?? 'None') }}
                  </td>
                  <td>
                      {{ __($order->customer->name) }}
                  </td>
                  <td>
                  {{ __(formatCurrency($order->total_amount)) }}
                  </td>
                  <td>
                  {{ __(formatCurrency($order->cost())) }}
                  </td>
                  <td>
                  {{ __($order->status) }}
                  </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
              @if($orders instanceof \Illuminate\Pagination\LengthAwarePaginator )
              <div class="justify-content-center">{{$orders->links()}}</div>
              @endif
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card card-plain">
          <div class="card-header card-header-primary">
            <h4 class="card-title mt-0">Expenses</h4>
            <p class="card-category"><label for="" class= "font-weight-bold" >Total Expenses: {{ formatCurrency($expenses_sum) }}</label> </p>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover">
                <thead class="">
                    <th> Date</th>
                    <th>Description</th>
                    <th>Amount</th>
                    
                </thead>
                <tbody>
                  @if(count($expenses)<=0)
                    <tr><td>No Expenses found</td></tr>
                    @endif
                  @foreach($expenses ?? [] as $expense)
                  <tr class="" data-id="{{__($expense->id)}}">
                  <td>
                  {{ __( $expense->value_date ?? 'None') }}
                  </td>
                  <td>
                      {{ __($expense->description) }}
                  </td>
                  <td>
                  {{ __($expense->amount) }}
                  </td>
                  
                  </tr>
                  @endforeach
                </tbody>
              </table>
              @if($expenses instanceof \Illuminate\Pagination\LengthAwarePaginator )
              <div class="justify-content-center">{{$expenses->links()}}</div>
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