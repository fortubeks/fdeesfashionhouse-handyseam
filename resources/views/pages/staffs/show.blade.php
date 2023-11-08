@extends('layouts.app', ['activePage' => 'staff', 'titlePage' => __('Staffs')])
@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card ">
          <div class="card-header card-header-primary">
            <h4 class="card-title mt-0">{{ __('Staff Details') }}</h4>
            <p class="card-category"> </p>
          </div>
          
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <div class="card ">
                  <form action="{{ url('staffs/'.$staff->id) }}" method="post">
                    @csrf
                  <div class="card-body ">
                    <div class="form-group bmd-form-group mb-4">
                      
                      <select name="role" data-role="{{ __($staff->role ?? '') }}" class="form-control" id="role" >
                        <option selected value="">Select Staff Type</option>
                        <option value="manager">Manager</option>
                        <option value="tailor">Tailor</option>
                      </select>
                    </div>
                    <div class="form-group bmd-form-group mb-4">
                      <label>First Name</label>
                      <input type="text"  value="{{ __($staff->first_name ?? '') }}" name="firstname" class="form-control" placeholder="First Name" >
                    </div>
                    <div class="form-group bmd-form-group mb-4">
                      <label>Last Name</label>
                      <input type="text"  value="{{ __($staff->last_name ?? '') }}" name="lastname" class="form-control" > 
                    </div>
                    <div class="form-group bmd-form-group mb-4">
                      <label>Phone Number</label>
                      <input type="text"  value="{{ __($staff->phone ?? '') }}" name="phone" class="form-control" >
                    </div>
                    <div class="form-group bmd-form-group mb-4">
                        <div class="custom-control custom-switch">  
                            @if($staff->user)
                            <input type="checkbox" class="custom-control-input" {{ (($staff->user->user_status==1)?'checked':'') }} id="user_status" name="user_status" value="{{ __($staff->user->user_status) }}">
                            <label class="custom-control-label" for="user_status">Enable/Disable</label>
                            @endif  
                            
                        </div>
                    </div>   
                  </div>
                  @if($staff->user)
                  <div class="row">
                    <div class="col-md-12">
                      <div class="card " id="account-div"><div class="card-header">
                        <h4 class="card-title mt-0">{{ __('For Manager Account Only (optional for tailor)') }}</h4></div>
                        <div class="card-body ">
                          <div class="form-group bmd-form-group mb-4">
                            <input type="email" name="email" class="form-control" value="{{ __($staff->user->email ?? '') }}" placeholder="Email Address"  >
                          </div>
                          <div class="form-group bmd-form-group mb-4">
                            <input type="text" name="password" class="form-control" value="" placeholder="Password" >
                          </div>
                        </div>
                      </div>
                      
                    </div>
                  </div>
                  @endif
                </div>
              </div>
              <div class="col-md-6">
                <div class="card ">
                  <div class="card-body ">
                    <div class="form-group bmd-form-group mb-4">
                      <label>Address</label>
                      <input type="text"  value="{{ __($staff->address ?? '') }}" name="address" class="form-control" placeholder="Address" >
                    </div>
                    <div class="form-group bmd-form-group mb-4">
                      <label>Salary</label>
                      <input type="text" name="salary_amount"  value="{{ __($staff->salary_amount ?? '') }}" class="form-control" placeholder="Salary" >  
                    </div>
                    <div class="form-group bmd-form-group mb-4">
                      <label>Account Details</label>
                      <input type="text" name="account_details"  value="{{ __($staff->account_details ?? '') }}" class="form-control" placeholder="Account Details" > 
                    </div>
                    <div class="form-group bmd-form-group mb-4">
                      <label>Other Information</label>
                      <input type="text" name="other_information"  value="{{ __($staff->other_information ?? '') }}" class="form-control" placeholder="Other Information" > 
                    </div>
                  </div>
                  
                </div>
                
                <div class="card-footer ">
                    <input type="hidden" name="_method" value="PUT">
                    <button type="submit" class="btn btn-primary">Update</button> 
                </div>
                 </form>
              </div>
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
    $('#role').change(function() {
   if($(this).val() == "manager"){
    $('#account-div').css("display","block");
   }
   if($(this).val() == "tailor"){
    $('#account-div').css("display","none");
   }
})
var role = $('#role').attr("data-role");
    $('#role option[value="'+role+'"]').attr('selected','selected');
});
</script>
