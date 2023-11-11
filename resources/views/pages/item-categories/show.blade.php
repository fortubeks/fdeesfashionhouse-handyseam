@extends('layouts.app', ['activePage' => 'expenses', 'titlePage' => __('Expenses')])

@section('content')
<div class="content">
  <div class="container-fluid">
    <form action="{{ url('/item-categories/'.$item_category->id) }}" method="post">
        @csrf
    <div class="row">
      <div class="col-md-4 mb-3"> 
        <input type="text" name="description" value="{{ __($item_category->name ?? 'None') }}" class="form-control" placeholder="Enter Item Category" aria-label="Enter Item Description"> 
      </div>
        
      <div class="col-md-4 mb-3">
          <button class="btn btn-primary btn-block" type="submit" id="">Update</button>
          <input type="hidden" name="_method" value="PUT">
      </div>
      <div class="col-md-4 mb-3">
          
      </div>
    </div>
    </form>
    
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
                    <th>For Sale</th>
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
                  {{ (($item->for_sale == 1)) ? 'Yes' : 'No' }}
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
<script>
window.addEventListener('load', function() {

$('.item').click(function() {
    var id = $(this).attr("data-id");
    var url = "{{ url('items/') }}/"+id;
    if(id) {
        window.location = url;
    }
})
});
</script>