@extends('layouts.app', ['activePage' => 'payments', 'titlePage' => __('Payments')])

@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-2 mb-3"> 
        <a href="{{ url('orders/') }}" class="btn btn-primary" >Add New Payment</a>
      </div>
        
      <div class="col-md-8 mb-3">
        <form action="{{ url('/payments-search') }}" method="get">
          @csrf
          <div class="row">
            <div class="col-md-4">
                <label>Start Date</label>
                <input class="form-control" name="start_date" type="date" >
            </div>
            <div class="col-md-4">
                <label>End Date</label>
                <input class="form-control" name="end_date" type="date" >
            </div>
            <div class="col-md-3">
              <button class="btn btn-primary btn-block bottom-left" type="submit" id="">Search</button>
            </div>  
          </div>
        </form>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card card-plain">
          <div class="card-header card-header-primary">
            <h4 class="card-title mt-0"> All Payments</h4>
            <p class="card-category"> View All Payments</p>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover">
                <thead class="">
                    <th>Payment Date</th>
                    <th>Customer Name</th>
                    <th>Mode Of Payment</th>
                    <th>Amount</th>
                </thead>
                <tbody>
                  @if(count($payments)<=0)
                    <tr><td>No Payment found</td></tr>
                    @endif
                  @foreach($payments ?? [] as $payment)
                  <tr class="item" data-id="{{__($payment->invoice->order->id)}}">
                  <td>
                  {{ __( $payment->created_at->format('d-m-Y') ?? 'None') }}
                  </td>
                  <td>
                      {{ __($payment->invoice->order->customer->name) }}
                  </td>
                  <td>
                  {{ __($payment->mode_of_payment) }}
                  </td>
                  <td>
                  {{ __($payment->amount ?? 'None') }}
                  </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
                                     
                    
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