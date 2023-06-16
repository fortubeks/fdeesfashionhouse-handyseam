@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Add New Customer Measurements') }}</div>

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
                    
                    <form action="{{ url('save-customer-measurements/'.$customer->id) }}" method="post">
                    @csrf
                    
                    <div class="form-row">
                        <?php
                        $appSetting = App\Models\AppSetting::where('user_id', Auth::user()->id)->first();
                        $user_measurement_details = json_decode($appSetting->measurement_details, true);
                        $customer_measurement_details = json_decode($customer->measurement_details, true);
                        //dd($user_measurement_details);
                        ?>
                        
                        @foreach($user_measurement_details as $key => $measurement_detail)
                            <div class="col-md-4 mb-3">
                                <label for="{{ __($key) }}" class="">{{ __($measurement_detail) }}</label>
                                <input type="text" name="{{ __($key) }}" class="form-control " value="{{ __($customer_measurement_details[$key] ?? '')  }}" placeholder=""> 
                            </div>
                        @endforeach
                        
                    </div>

                        
                        
                    
                      
                        <div class="form-group">
                        
                        <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>

            </div>
            
            
            
        </div>
    </div>
</div>
@endsection
