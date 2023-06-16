@extends('layouts.app', ['activePage' => 'items', 'titlePage' => __('Items')])
@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card ">
          <div class="card-header card-header-primary">
            <h4 class="card-title mt-0">{{ __('Add New Item to Shop') }}</h4>
            <p class="card-category"> </p>
          </div>
            
          <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="card ">
                        <form action="{{ url('items/') }}" method="post">
                            @csrf
                            <div class="card-body ">
                                <div class="form-group bmd-form-group mb-4">
                                    <label>Select Category (<a href="{{url('/items-categories')}}">Create New Category</a>)</label>
                                    <select class="form-select" name="category_id">
                                        @foreach (getModelList('item-categories') as $item_category)
                                        <option value="{{ __($item_category->id) }}">{{ __($item_category->name) }}</option>
                                        @endforeach
                                    </select> 
                                </div>
                                <div class="form-group bmd-form-group mb-4">
                                    <label>Name/Description</label>
                                    <input type="text" required name="description" class="form-control" placeholder="Enter Item Description" aria-label="Enter Item Description">
                                </div>
                                <div class="form-group bmd-form-group mb-4">
                                    <label>Select Unit of Measurement</label>
                                    <select class="form-select" name="unit_measurement">
                                        <option value="">--Select--</option>
                                        <option value="piece">Piece</option>
                                        <option value="roll">Roll</option>
                                        <option value="roll">Yard</option>
                                    </select> 
                                </div>
                                <div class="form-group bmd-form-group mb-4">
                                    <label>For Sale</label>
                                    <input type="checkbox" id="for_sale" name="for_sale" value="1" class="form-check" >
                                </div>
                                <div id="sell" class="form-group bmd-form-group mb-4" style="display: none;">
                                    <label>Selling Price</label>
                                    <input type="number" name="price" value="0" class="form-control" >
                                </div> 
                                <div class="form-group bmd-form-group mb-4">
                                    <label>Quantity in Stock</label>
                                    <input type="number" required name="qty" class="form-control" placeholder="Enter Quantity in Stock" value="0" aria-label="Quantity in Stock">
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
  $("#for_sale").change(function() {
    if($("#for_sale").prop('checked')) {
        //Show Selling Price
        $('#sell').show();
    }
    else {
        //Hide Selling Price
        $('#sell').hide();
    }
});
});
</script>
