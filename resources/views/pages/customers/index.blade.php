@extends('layouts.app', ['activePage' => 'customers', 'titlePage' => __('Customers')])

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
            <div class="col-md-6">
              <div class="input-group">
                <div class="input-group-prepend">
                  <div class="input-group-text"><i class="material-icons">face</i></div>
                </div>
                <input type="text" name="search_value" class="form-control" placeholder="Enter customer name or phone number to search for customer" >
              </div>
            </div>
            <div class="col-md-3">

              <select class="form-select" name="search_by" aria-label=".form-select-sm example">
                <option selected value="name">Search By Name</option>
                <option value="phone">Search By Phone</option>
              </select>
            </div>
            <div class="col-md-3">
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
            
            <div class="row">
              <div class="col-md-10">
                <h4 class="card-title mt-0"> Result/List</h4>
                <p class="card-category"> </p>
              </div>
              <div class="col-md-2">
                <a href="{{url('/export-customers')}}" class="btn btn-primary">Export</a>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover">
                <thead class="">
                    <th style="width:35%">Name</th>
                    <th style="width:30%">Phone</th>
                    <th style="width:20%">Number of Orders</th>
                    <th style="width:15%">Total Amount</th>
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
                  <td style="text-align: center;">
                    {{ __($customer->getTotalNumberOfOrders()) }}
                  </td>
                  <td>
                     {{ __($customer->getTotalAmountOnAllOrders()) }}
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
                                  <input type="text" name="name" required class="form-control" placeholder="Customer Name...">
                                </div>
                            </div>

                            <div class="form-group bmd-form-group">
                                <div class="input-group">
                                  <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="material-icons">face</i></div>
                                  </div>
                                  <input type="hidden" id="parent_id" name="parent_id">
                                  <input oninput="setParentID()" class="form-control" list="customerdatalistOptions" id="customer" placeholder="Parent Customer if anny">
                                  <datalist id="customerdatalistOptions">
                                      @foreach(getModelList('customers') as $customer)
                                      <option value="{{$customer->name}}" data-value="{{$customer->id}}">
                                      @endforeach
                                  </datalist>
                                </div>
                            </div>

                            <div class="form-group bmd-form-group">
                                <div class="input-group">
                                  <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="material-icons">phone</i></div>
                                  </div>
                                  <input name="phone" type="text" class="form-control" placeholder="Phone...">
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
    var url = "{{ url('customers/') }}/"+id;
    if(id) {
        window.location = url;
    }
})

});
function setParentID(){
    var value = $('#customer').val();
    $('#parent_id').val($('#customerdatalistOptions [value="' + value + '"]').data('value'));
}
</script>