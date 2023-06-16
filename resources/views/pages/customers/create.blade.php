@extends('layouts.app', ['activePage' => 'customers', 'titlePage' => __('Customer')])
@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card ">
          <div class="card-header card-header-primary">
            <h4 class="card-title mt-0">{{ __('Add New Customer') }}</h4>
            <p class="card-category"> </p>
          </div>
            
          <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="card ">
                        <form action="{{ url('customers/') }}" method="post">
                            @csrf
                            <div class="card-body">

                                <div class="form-group bmd-form-group">
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="material-icons">face</i></div>
                                    </div>
                                    <input type="text" name="name" class="form-control" placeholder="Customer Name...">
                                    </div>
                                </div>

                                <div class="form-group bmd-form-group">
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="material-icons">phone</i></div>
                                    </div>
                                    <input name="phone" required type="text" class="form-control" placeholder="Phone...">
                                    </div>
                                </div>

                                <div class="form-group bmd-form-group">
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="material-icons">email</i></div>
                                    </div>
                                    <input name="email" type="email" class="form-control" placeholder="Email ..."></textarea> 
                                    </div>
                                </div>

                                <div class="form-group bmd-form-group">
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="material-icons">map</i></div>
                                    </div>
                                    <textarea name="address" rows="2" class="form-control" value="" placeholder="Address ..."></textarea> 
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
</div>
@endsection

<script>
window.addEventListener('load', function() {

});
</script>
