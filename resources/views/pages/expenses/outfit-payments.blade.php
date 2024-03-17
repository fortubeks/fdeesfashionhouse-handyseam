@extends('layouts.app', ['activePage' => 'payments', 'titlePage' => __('Payments')])

@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-2 mb-3"> 
         <label>Search Customer</label>
      </div>
        
      <div class="col-md-8 mb-3">
        <form action="{{ url('/outfit-payments-customer-search') }}" method="get">
          <div class="row">
            <div class="col-md-6">
                <input class="form-control" name="name" type="text" >
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
            <h4 class="card-title mt-0"> Tailor Payments</h4>
            <p class="card-category"> Manage payments to tailors by outfits made</p>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover">
                <thead class="">
                    <th>Tailor Name</th>
                    <th>Customer</th>
                    <th>Style</th>
                    <th>Fitting Date</th>
                    <th>Status</th>
                    <th>Amount</th>
                    <th>Date of Payment</th>
                </thead>
                <tbody>
                  @if(count($tailor_payments_by_outfits_made_weekly)<=0)
                    <tr><td>No Payment found</td></tr>
                    @endif
                  @foreach($tailor_payments_by_outfits_made_weekly ?? [] as $payment)
                  <tr class="item" data-id="0">
                  <td>
                  {{ __( $payment->tailor ?? 'None') }}
                  </td>
                  <td>
                      {{ __($payment->customer) }}
                  </td>
                  <td>
                  {{ __($payment->style) }}
                  </td>
                  <td>
                  {{ __($payment->fitting_date) }}
                  </td>
                  <td>
                  {{ __($payment->status) }}
                  </td>
                  <td>
                  {{ __($payment->amount) }}
                  </td>
                  <td>
                    <input type="date" value="{{(($payment->payment_date)) ? $payment->payment_date : ''}}" class="form-control paymentDate" data-id="{{$payment->outfit_id}}">
                  
                  </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
              @if($tailor_payments_by_outfits_made_weekly instanceof \Illuminate\Pagination\LengthAwarePaginator )
                <div class="justify-content-center">{{$tailor_payments_by_outfits_made_weekly->appends(request()->query())->links()}}</div>
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
   
})
$('.paymentDate').change(function() {
   params = "id="+ $(this).attr('data-id') + "&value="+$(this).val();
   $.ajax({
            url: "{{ url('/tailor-payment-date-update') }}",
            type: "GET",
            data: params,
            success: function(data){
                data = JSON.parse(data);
            }
        });
  })
});
</script>