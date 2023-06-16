@extends('layouts.app', ['activePage' => 'items', 'titlePage' => __('Items')])
@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card ">
          <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="card ">
                      <div class="card-header card-header-primary">
                        <h4 class="card-title mt-0">{{ __('Item Details') }}</h4>
                      </div>
                        <form action="{{ url('items/'.$item->id) }}" method="post">
                            @csrf
                            <div class="card-body ">
                                <div class="form-group bmd-form-group mb-4">
                                    <label>Select Category</label>
                                    <select class="form-select" id="category" data-category="{{ __($item->item_category_id) }}" name="category_id">
                                        @foreach (getModelList('item-categories') ?? [] as $item_category)
                                        <option value="{{ __($item_category->id) }}">{{ __($item_category->name) }}</option>
                                        @endforeach
                                    </select> 
                                </div>
                                <div class="form-group bmd-form-group mb-4">
                                    <label>Name/Description</label>
                                    <input type="text" name="description" value="{{ __($item->description ?? 'None') }}" class="form-control" placeholder="Enter Item Description" aria-label="Enter Item Description">
                                </div>
                                <div class="form-group bmd-form-group mb-4">
                                    <label>Cost Price</label>
                                    <input type="number" name="cost_price" step=".001" min="0" value="{{ __($item->cost_price ?? 'None') }}" class="form-control" placeholder="Enter Cost Price" aria-label="Enter Cost Price">
                                </div> 
                                <div class="form-group bmd-form-group mb-4">
                                    <label>Sale Price</label>
                                    <input type="number" name="price" step=".001" min="0" value="{{ __($item->price ?? 'None') }}" class="form-control" placeholder="Enter Unit Price" aria-label="Enter Unit Price">
                                </div> 
                                <div class="form-group bmd-form-group mb-4">
                                    <label>Quantity</label>
                                    <input type="number" name="qty" value="{{ __($item->inventory_quantity ?? 'None') }}" class="form-control" placeholder="Enter Quantity in Stock" aria-label="Quantity in Stock">
                                </div>
                                <div class="form-group bmd-form-group mb-4">
                                    <label>For Sale</label>
                                    <input type="checkbox" name="for_sale" {{(( $item->for_sale == 1)) ? 'checked' : '' }} class="form-check" >
                                </div> 
                            </div>
                            <div class="card-footer ">
                                <input type="hidden" name="_method" value="PUT">
                                <button type="submit" class="btn btn-primary">Update</button> 
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-8">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="card ">
                        <div class="card-header card-header-primary">
                          <h4 class="card-title mt-0">{{ __('Inventory History') }}</h4>
                        </div>
                        <div class="card-body">
                          <div class="table-responsive">
                            <table class="table table-hover">
                              <thead class="">
                                  <th>Date</th>
                                  <th>Customer</th>
                                  <th>Qty</th>
                              </thead>
                              <tbody>
                                @forelse($item->items_used as $item_used)
                                <tr class="item" data-id="{{__($item_used->id)}}">
                                <td>
                                {{ __($item_used->created_at->format('d-M-Y')) }}
                                </td>
                                <td>
                                {{ __($item_used->outfit->order->customer->name) }}
                                </td>
                                <td>
                                {{ __($item_used->qty) }}
                                </td>
                                <td>
                                </tr>
                                @empty
                                <tr><td>None</td></tr>
                                @endforelse
                              </tbody>
                            </table> 
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="card ">
                        <div class="card-header card-header-primary">
                          <h4 class="card-title mt-0">{{ __('Purchase History') }}</h4>
                        </div>
                        <div class="card-body">
                          <div class="table-responsive">
                            <table class="table table-hover">
                              <thead class="">
                                  <th>Date</th>
                                  <th>Qty</th>
                                  <th>Amount</th>
                              </thead>
                              <tbody>
                                @forelse($item->purchases as $purchase)
                                <tr class="item" data-id="{{__($purchase->id)}}">
                                <td>
                                {{ __($purchase->created_at->format('d-M-Y')) }}
                                </td>
                                <td>
                                {{ __($purchase->qty) }}
                                </td>
                                <td>
                                {{ __(formatCurrency($purchase->amount)) }}
                                </td>
                                <td>
                                </tr>
                                @empty
                                <tr><td>None</td></tr>
                                @endforelse
                              </tbody>
                            </table> 
                          </div>
                        </div>
                      </div>
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
    var category = $('#category').attr("data-category");
    $('#category option[value="'+category+'"]').attr('selected','selected');
});
</script>
