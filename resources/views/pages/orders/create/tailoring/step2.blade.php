@extends('layouts.app', ['activePage' => 'orders', 'titlePage' => __('Orders')])

@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card card-plain">
          <div class="card-header card-header-primary">
            <h4 class="card-title mt-0">{{ __('Measurement Details For '.$customer->name ) }}</h4>
            <p class="card-category"> </p>
          </div>
          <div class="card-body">
            <div class="">
                <form action="{{ url('save-customer-measurements/'.$customer->id) }}" method="post">
                @csrf
                <div class="form-row">
                    <?php
                    $appSetting = App\Models\Setting::where('user_id',auth()->user()->user_account_id)->first();
                    $user_measurement_details = json_decode($appSetting->measurement_details, true);
                    $customer_measurement_details = json_decode($customer->measurement_details, true);
                    ?>             
                    @foreach($user_measurement_details as $key => $measurement_detail)
                        <div class="col-md-3 mb-3">
                            <label for="{{ __($key) }}" class="">{{ __($measurement_detail) }}</label>
                            <input type="text" name="{{ __($key) }}" class="form-control " value="{{ __($customer_measurement_details[$key] ?? '') }}" placeholder=""> 
                        </div>
                    @endforeach
                    
                </div>
                <input type="hidden" name="customer_id" value="{{ __($customer->id ?? 'None') }}">
                <input type="hidden" name="origin" value="order_creation">
                
                
                <div class="form-group"> 
                    <button type="submit" class="btn btn-primary">Save & Go To Style Design</button>
                    @if(auth()->user()->user_account->app_settings->measurement_set == 0)
                    <br><br><br><span>Click <a class="btn btn-success" href="{{ url('update-measurement-settings/?origin=create-order&customer_id='.$customer->id) }}">Here</a> To Configure Measurement Details If The Default Doesn't Suit Your Measuring Style</span>
                    @endif
                </div>
                </form>
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
    
});
</script>