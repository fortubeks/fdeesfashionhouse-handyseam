@extends('layouts.app', ['activePage' => 'items', 'titlePage' => __('Items')])

@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-2 mb-3"> 
        <a href="{{ url('items/create') }}" class="btn btn-primary" >Add New Item To Shop</a>
      </div>
        
      <div class="col-md-10 mb-3">
        <form action="{{ url('/items-search') }}" method="get">
          <div class="row">
            <div class="col-md-4">
              <div class="input-group">
                <div class="input-group-prepend">
                  <div class="input-group-text"><i class="material-icons">inventory</i></div>
                </div>
                <input type="text" name="search_value" class="form-control" placeholder="Enter Item Name" >
              </div>
            </div>
            <div class="col-md-4">

              <select class="form-select" name="search_by" aria-label=".form-select-sm example">
                    <option value="desc">Search By Item Description</option>
                    <option value="sku">Search By SKU</option>
              </select>
            </div>
            <div class="col-md-2">
              <button class="btn btn-primary btn-block" type="submit" id="">Search</button>
            </div>
            <div class="col-md-2">
              <a href="{{ url('item-categories/') }}" class="btn btn-link btn-block">Manage Categories</a>
            </div>  
          </div>
        </form>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card card-plain">
          <div class="card-header card-header-primary">
            <h4 class="card-title mt-0"> Result/List</h4>
            <p class="card-category"> </p>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover">
                <thead class="">
                    <th>SKU</th>
                    <th>Item Name</th>
                    <th>Qty in Stock</th>
                    <th>Unit Price</th>
                    <th></th>
                </thead>
                <tbody>
                  @if(count($items)<=0)
                    <tr><td>No Items found</td></tr>
                    @endif
                  @foreach($items ?? [] as $item)
                  <tr class="item" data-id="{{__($item->id)}}">
                  <td>
                  {{ __($item->sku ?? 'None') }}
                  </td>
                  <td>
                  {{ __($item->description) }}
                  </td>
                  <td>
                  {{ __($item->inventory_quantity) }}
                  </td>
                  <td>
                  @if($item->price){{ __(formatCurrency($item->price)) }} @endif
                  </td>
                  <td class="text-right">
                      <div class="dropdown">
                          <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <i class="fas fa-ellipsis-v"></i>
                          </a>
                          <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                              <a class="dropdown-item" href="{{url('items/'.$item->id)}}">Show/Edit</a>
                              <a class="dropdown-item" onclick="setItem(<?=$item->id?>)" data-toggle="modal" data-target="#loginModal" href="#">Add Purchase</a>
                          </div>
                      </div>
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
<div class="modal fade" id="loginModal" tabindex="-1" role="">
    <div class="modal-dialog modal-login" role="document">
        <div class="modal-content">
            <div class="card card-signup card-plain">
                <div class="modal-header">
                  <div class="card-header card-header-primary text-center" style="width: 100%;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                      <i class="material-icons">clear</i>
                    </button>
                    <h4 class="card-title">Add New Purchase</h4> 
                  </div>
                </div>
                <div class="modal-body">
                    <form class="form" method="post" action="{{ url('purchases/') }}">
                        @csrf
                        <p class="description text-center">Add New Purchase Details</p>
                        <div class="card-body">

                            <div class="form-group bmd-form-group mb-4">
                              <select class="form-select" name="item_id" id="item">
                                  @foreach (getModelList('items') as $item)
                                  <option value="{{ __($item->id) }}">{{ __($item->description) }}</option>
                                  @endforeach
                              </select>
                            </div>

                            <div class="form-group bmd-form-group mb-4">
                              <label>Quantity</label>
                              <input type="number" required name="qty" id="qty" onkeyup="updateTotal()" inputmode="decimal" min="0" step="any" class="form-control" placeholder="Enter Quantity" >
                            </div>

                            <div class="form-group bmd-form-group mb-4">
                              <label >Unit Cost</label>
                              <input type="number" required name="unit_cost" onkeyup="updateTotal()" inputmode="decimal" min="0" step="any" id="unit_cost" class="form-control" placeholder="Enter Unit Cost" >
                            </div>

                            <div class="form-group bmd-form-group mb-4">
                              <label>Amount</label>
                              <input type="number" required name="amount" id="amount" class="form-control" readonly >
                            </div>

                            <div class="form-group bmd-form-group mb-4">
                              <label >Date</label>
                              <input type="date" required name="created_at" class="form-control" >
                            </div>

                            <div class="form-group bmd-form-group mb-4">
                              <label >Note</label>
                              <textarea name="notes" rows="2" class="form-control" value="" placeholder="Note ..."></textarea> 
                                
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
    var url = "{{ url('items/') }}/"+id;
    if(id) {
        //window.location = url;
    }
})
});
function updateTotal(){
    var qty = $('#qty').val();
    var unit_cost = $('#unit_cost').val();

    var amount = qty*unit_cost;
    round_figure = parseFloat(amount.toFixed(2));
    $('#amount').val(round_figure);
}
function setItem(id){
  $('#item option[value="'+id+'"]').attr('selected','selected');
}
</script>