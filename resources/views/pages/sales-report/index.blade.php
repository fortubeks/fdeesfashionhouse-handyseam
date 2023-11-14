@extends('layouts.app', ['activePage' => 'report', 'titlePage' => __('Sales Report')])

@section('content')
<div class="content">
  <div class="container-fluid">
    <form action="{{ url('/sales-report/view') }}" id="form-get-report" method="post">
      @csrf
    <div class="row">
      <div class="col-md-2">
        <select name="date_range" class="form-select form-control filter-by-status" id="filter-by-status">
            <option selected value="">Filter By Status</option>
            <option value="this">This Month</option>
            <option value="last">Last Month</option>
            <option value="last-3">Last 3 Months</option>
            <option value="last-6">Last 6 Months</option>
            <option value="this-y">This Year</option>
            <option value="all-time">All Time</option>
        </select>
      </div>
      <div class="col-md-2">
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1">FROM</span>
          </div>
          <input name="start_date" class="form-control" @if(isset($start_date)) value="{{$start_date}}" @else value="{{date(auth()->user()->created_at->format('Y-m-d'))}}" @endif type="date" required>
        </div>
      </div>
      <div class="col-md-2">
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1">TO</span>
          </div>
          <input name="end_date" class="form-control" @if(isset($end_date)) value="{{$end_date}}" @else value="{{date('Y-m-d')}}" @endif type="date" required>
        </div>
      </div>
      <div class="col-md-2">
        <button class="btn btn-primary" type="submit">Filter</button>
      </div>
    </div>
    </form>
    <div class="row">
      <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
          <div class="card-header card-header-info card-header-icon">
            <div class="card-icon">
              <i class="material-icons">shopping_cart</i>
            </div>
            <p class="card-category">Sales/Revenue</p>
            <h3 class="card-title">{{isset($revenue) ? formatCurrency($revenue) : formatCurrency($orders_sum) }}
              
            </h3>
          </div>
          <div class="card-footer" style="width:100%">
            <div class="row" style="width:100%">
              <div class="col-6">
                
              </div>
              <div class="col-6">
                
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6 d-none d-md-block">
        <div class="card card-stats">
          <div class="card-header card-header-info card-header-icon">
            <div class="card-icon">
              <i class="material-icons">store</i>
            </div>
            <p class="card-category">Orders</p>
            <h3 class="card-title">{{$orders_count}}</h3>
          </div>
          <div class="card-footer">
            <div class="stats">
              
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
          <div class="card-header card-header-info card-header-icon">
            <div class="card-icon">
              <i class="material-icons">groups</i>
            </div>
            <p class="card-category">Expenses</p>
            <h3 class="card-title">{{formatCurrency($expenses_sum) }}</h3>
          </div>
          <div class="card-footer" style="width:100%">
            <div class="row" style="width:100%">
              <div class="col-6">
                <div class="stats">
                  
                </div>
              </div>
              <div class="col-6">
                <div class="stats">
                  
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6 d-none d-md-block">
        <div class="card card-stats">
          <div class="card-header card-header-info card-header-icon">
            <div class="card-icon">
              <i class="material-icons">inventory</i>
            </div>
            <p class="card-category">Profit</p>
            <h3 class="card-title">{{formatCurrency($profit)}}</h3>
          </div>
          <div class="card-footer">
            <div class="stats">
              
            </div>
          </div>
        </div>
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
$('#filter-by-status').on('change', function() {
  $('#form-get-report').submit()
});

});
</script>