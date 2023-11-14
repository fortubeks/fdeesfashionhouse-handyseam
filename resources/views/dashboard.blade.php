@extends('layouts.app', ['activePage' => 'dashboard', 'titlePage' => __('Dashboard')])

@section('content')
  <div class="content">
    <div class="container-fluid">
      @can('create', '\App\Models\User')
      <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6">
          <div class="card card-stats">
            <div class="card-header card-header-info card-header-icon">
              <div class="card-icon">
                <i class="material-icons">shopping_cart</i>
              </div>
              <p class="card-category">Orders Pending</p>
              <h3 class="card-title">{{count(auth()->user()->orders)}}
                
              </h3>
            </div>
            <div class="card-footer" style="width:100%">
            <div class="row" style="width:100%">
                <div class="col-6">
                  <div class="stats">
                    <i class="material-icons text-danger">shopping_cart</i>
                    <a href="{{url('/orders')}}">View all orders</a>
                  </div>
                </div>
                <div class="col-6">
                  <div class="stats">
                    <i class="material-icons">shopping_cart</i>
                    <a href="{{url('/orders/create')}}">Create Order</a>
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
                <i class="material-icons">store</i>
              </div>
              <p class="card-category">Revenue</p>
              <h3 class="card-title">{{formatCurrency(auth()->user()->orders->sum('total_amount'))}}</h3>
            </div>
            <div class="card-footer">
              <div class="stats">
                <a href="{{url('/sales-report')}}">
                <i class="material-icons">date_range</i> View Sales Report
                </a>
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
              <p class="card-category">Customers</p>
              <h3 class="card-title">{{count(auth()->user()->customers)}}</h3>
            </div>
            <div class="card-footer" style="width:100%">
              <div class="row" style="width:100%">
                <div class="col-6">
                  <div class="stats">
                    <i class="material-icons">person</i>
                    <a href="{{url('/customers')}}"> View all Customers</a>
                  </div>
                </div>
                <div class="col-6">
                  <div class="stats">
                    <i class="material-icons">person</i>
                    <a href="{{url('/customers/create')}}"> Add Customer</a>
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
              <p class="card-category">Items For Sale</p>
              <h3 class="card-title">{{count(auth()->user()->items)}}</h3>
            </div>
            <div class="card-footer">
              <div class="stats">
                <a href="{{url('/items')}}">
                <i class="material-icons">local_offer</i> Visit Inventory
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
      @endcan
      <div style="display:none" class="row">
        <div class="col-md-4">
          <div class="card card-chart">
            <div class="card-header card-header-primary" style="background: linear-gradient(60deg, #2A1A3F, #454578);">
              <div class="ct-chart" id="dailySalesChart"></div>
            </div>
            <div class="card-body">
              <h4 class="card-title">Daily Sales</h4>
              <p class="card-category">
                <span class="text-success"><i class="fa fa-long-arrow-up"></i> 55% </span> increase in today sales.</p>
            </div>
            <div class="card-footer">
              <div class="stats">
                <i class="material-icons">access_time</i> updated 4 minutes ago
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card card-chart">
            <div class="card-header card-header-primary" >
              <div class="ct-chart" id="websiteViewsChart"></div>
            </div>
            <div class="card-body">
              <h4 class="card-title">Customers</h4>
              <p class="card-category">Last Campaign Performance</p>
            </div>
            <div class="card-footer">
              <div class="stats">
                <i class="material-icons">access_time</i> campaign sent 2 days ago
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card card-chart">
            <div class="card-header card-header-primary" style="background: linear-gradient(60deg, #2A1A3F, #454578);">
              <div class="ct-chart" id="completedTasksChart"></div>
            </div>
            <div class="card-body">
              <h4 class="card-title">Completed Jobs</h4>
              <p class="card-category">Last Campaign Performance</p>
            </div>
            <div class="card-footer">
              <div class="stats">
                <i class="material-icons">access_time</i> campaign sent 2 days ago
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-6 col-md-12">
        <div class="card">
            <div class="card-header card-header-primary">
              <h4 class="card-title">Orders Due This Week</h4>
              <p class="card-category"></p>
            </div>
            <div class="card-body table-responsive">
              <table class="table table-hover">
                <thead class="text-primary">
                  <th>Order Date</th>
                  <th>Customer Name</th>
                  @if(auth()->user()->user_type != 'tailor')
                  <th>Amount</th>
                  @endif
                  <th>Fitting Date</th>
                </thead>
                <tbody>
                  @if(count($orders_due)<=0)
                    <tr><td>No Orders found</td></tr>
                    @endif
                  @foreach($orders_due ?? [] as $order)
                  <tr class="item" data-id="{{__($order->id)}}">
                  <td>
                  {{ __( $order->created_at->format('d-M-y h:i:s') ?? 'None') }}
                  </td>
                  <td>
                      {{ __($order->customer->name) }}
                  </td>
                  @if(auth()->user()->user_type != 'tailor')
                  <td>
                  {{ __(formatCurrency($order->total_amount) ?? 'None') }}
                  </td>
                  @endif
                  <td>
                  <?php $exp_date = date('d-M-Y', strtotime($order->expected_delivery_date));?>
                  {{ __($exp_date) }}
                  </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
              @if($orders_due instanceof \Illuminate\Pagination\LengthAwarePaginator )
              <div class="justify-content-center">{{$orders_due->links()}}</div>
              @endif
            </div>
          </div>
        </div>
        <div class="col-lg-6 col-md-12">
          <div class="card">
            <div class="card-header card-header-primary">
              <h4 class="card-title">Recent Orders</h4>
              <p class="card-category"></p>
            </div>
            <div class="card-body table-responsive">
              <table class="table table-hover">
                <thead class="text-primary">
                  <th>Order Date</th>
                  <th>Customer Name</th>
                  <th>Fitting Date</th>
                  <th>Status</th>
                </thead>
                <tbody>
                  @if(count($orders_recent)<=0)
                    <tr><td>No Orders found</td></tr>
                    @endif
                  @foreach($orders_recent ?? [] as $order)
                  <tr class="item" data-id="{{__($order->id)}}">
                  <td>
                  {{ __( $order->created_at->format('d-M-y h:i:s') ?? 'None') }}
                  </td>
                  <td>
                      {{ __($order->customer->name) }}
                  </td>
                  <td>
                  <?php $exp_date = date('d-M-Y', strtotime($order->expected_delivery_date));?>
                  {{ __($exp_date) }}
                  </td>
                  <td>
                  {{ __($order->status ?? 'None') }}
                  </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
              @if($orders_recent instanceof \Illuminate\Pagination\LengthAwarePaginator )
              <div class="justify-content-center">{{$orders_recent->links()}}</div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('js')
  <script>
    $(document).ready(function() {
      // Javascript method's body can be found in assets/js/demos.js
      //md.initDashboardPageCharts();
    });
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
@endpush