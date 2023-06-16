@extends('layouts.app', ['activePage' => 'orders', 'titlePage' => __('Orders')])

@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-2 mb-3"> 
        <button class="btn btn-primary" data-toggle="modal" data-target="#loginModal">
            Create New Customer
        </button>
      </div>
        
      <div class="col-md-10 mb-3">
        <form action="{{ url('/customers-search') }}" method="get">
          <div class="row">
            <div class="col-md-6  mb-3">
              <div class="input-group">
                <div class="input-group-prepend">
                  <div class="input-group-text"><i class="material-icons">face</i></div>
                </div>
                <input type="text" name="search_value" class="form-control" placeholder="Enter name or phone number to search for customer" >
              </div>
            </div>
            <div class="col-md-3 mb-3">

              <select class="form-select" name="search_by" aria-label=".form-select-sm example">
                <option selected value="name">Search By Customer Name</option>
                <option value="phone">Search By Customer Phone</option>
              </select>
            </div>
            <div class="col-md-3">
                <input type="hidden" name="origin" value="order_creation"/>
                <input type="hidden" name="order_type" value="tailoring"/>
                <button class="btn btn-primary" type="sumbit" id="">Search</button>
            </div>  
          </div>
        </form>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card card-plain">
          <div class="card-header card-header-primary">
            <h4 class="card-title mt-0"> Select a Customer</h4>
            <p class="card-category"> </p>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover">
                <thead class="">
                    <th style="width:35%">Name</th>
                    <th style="width:30%">Phone</th>
                </thead>
                <tbody>
                  @if(count($customers)<=0)
                    <tr><td>No Customers found</td></tr>
                    @endif
                  @foreach($customers ?? [] as $customer)
                  <tr class="item" data-id="{{__($customer->id)}}">
                  <td>
                    {{ __($customer->name ?? 'None') }}
                  </td>
                  <td>
                    {{ __($customer->phone) }}
                  </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
              {{$customers->links()}}                     
                    
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
<div class="modal fade" id="loginModal" tabindex="-1" role="">
    <div class="modal-dialog modal-login" role="document">
        <div class="modal-content">
            <div class="card card-signup card-plain">
                <div class="modal-header">
                  <div class="card-header card-header-primary text-center" style="width: 100%;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                      <i class="material-icons">clear</i>
                    </button>
                    <h4 class="card-title">Create New Customer</h4> 
                  </div>
                </div>
                <div class="modal-body">
                    <form class="form" method="post" action="{{ url('customers/') }}">
                        @csrf
                        <p class="description text-center">Add Customer Details</p>
                        <div class="card-body">

                            <div class="form-group bmd-form-group">
                                <div class="input-group">
                                  <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="material-icons">face</i></div>
                                  </div>
                                  <input type="text" required name="name" class="form-control" placeholder="Customer Name...">
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
                    
                </div>
                <div class="modal-footer justify-content-center">
                    <input type="hidden" name="origin" value="order_creation">
                    <input type="hidden" name="order_type" value="tailoring"/>

                    <button type="submit" class="btn btn-primary btn-link btn-wd btn-lg">Create</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
window.addEventListener('load', function() {
    $('.item').click(function() {
    var id = $(this).attr("data-id");
    var url = "{{ url('/create-order/tailoring/step2') }}/"+id;
    if(id) {
        window.location = url;
    }
})
$('.create-customer').click(function() {
    var id = $(this).attr("data-id");
    var url = "{{ url('orders/') }}/"+id;
    if(id) {
        window.location = url;
    }
})
});
</script>