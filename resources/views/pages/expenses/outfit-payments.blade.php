@extends('layouts.app', ['activePage' => 'payments', 'titlePage' => __('Payments')])

@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-2 mb-3"> 
         <label>Select Week Ending</label>
      </div>
        
      <div class="col-md-8 mb-3">
        <form action="{{ url('/outfit-payments-search') }}" method="get">
          @csrf
          <div class="row">
            <div class="col-md-6">
                <input class="form-control" name="week_ending" type="week" >
            </div>
            
            <div class="col-md-4">
              <button class="btn btn-primary btn-block bottom-left" type="submit" id="">Filter</button>
            </div>  
          </div>
        </form>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card card-plain">
          <div class="card-header card-header-primary">
            <h4 class="card-title mt-0"> Outfit Tailor Payments</h4>
            <p class="card-category"> View All Payments</p>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover">
                <thead class="">
                    <th>Tailor Name</th>
                    <th>Completed Outfits</th>
                    <th>Amount Due</th>
                    <th>Payment Status</th>
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
                      {{ __($payment->completed_outfits) }}
                  </td>
                  <td>
                  {{ __($payment->total_amount_due) }}
                  </td>
                  <td>
                  {{ __($payment->status) }}
                  </td>
                  <td>
                  {{ __($payment->date_of_payment) }}
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
   
})
});
</script>