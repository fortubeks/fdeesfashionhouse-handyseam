@extends('layouts.app', ['activePage' => 'staff', 'titlePage' => __('Staffs')])
@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card ">
          <div class="card-header card-header-primary">
            <h4 class="card-title mt-0">{{ __('Add Staff') }}</h4>
            <p class="card-category"> </p>
          </div>
          
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <div class="card ">
                  <form action="{{ url('staffs/') }}" method="post">
                    @csrf
                  <div class="card-body ">
                    <div class="form-group bmd-form-group mb-4">
                      
                      <select name="role" class="form-control" id="role" required>
                        <option selected value="">Select Staff Type</option>
                        <option value="manager">Manager</option>
                        <option value="tailor">Tailor</option>
                      </select>
                    </div>
                    <div class="form-group bmd-form-group mb-4">
                      <label>First Name</label>
                      <input type="text" name="firstname" class="form-control" placeholder="First Name" required>
                    </div>
                    <div class="form-group bmd-form-group mb-4">
                      <label>Last Name</label>
                      <input type="text" name="lastname" class="form-control" > 
                    </div>
                    <div class="form-group bmd-form-group mb-4">
                      <label>Phone Number</label>
                      <input type="text" name="phone" class="form-control" required>
                    </div>  
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card ">
                  <div class="card-body ">
                    <div class="form-group bmd-form-group mb-4">
                      <label>Address</label>
                      <input type="text" name="address" class="form-control" placeholder="Address" required>
                    </div>
                    <div class="form-group bmd-form-group mb-4">
                      <label>Salary</label>
                      <input type="number" step=".001" min="0" name="salary_amount" class="form-control" placeholder="Salary" >  
                    </div>
                    <div class="form-group bmd-form-group mb-4">
                      <label>Account Details</label>
                      <input type="text" name="account_details" class="form-control" placeholder="Account Details" > 
                    </div>
                    <div class="form-group bmd-form-group mb-4">
                      <label>Other Information</label>
                      <input type="text" name="other_information" class="form-control" placeholder="Other Information" > 
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="card " id="account-div"><div class="card-header">
                  <h4 class="card-title mt-0">{{ __('For Manager Account Only (optional for tailor)') }}</h4></div>
                  <div class="card-body ">
                    <div class="form-group bmd-form-group mb-4">
                      <input type="email" name="email" class="form-control" placeholder="Email Address"  >
                    </div>
                    <div class="form-group bmd-form-group mb-4">
                      <input type="password" name="password" class="form-control" placeholder="Password" >
                    </div>
                  </div>
                </div>
                <div class="card-footer ">
                  <button type="submit" class="btn btn-primary">Create</button>  
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
    $('#account-div').css("display","block");
   }
})
});
</script>
