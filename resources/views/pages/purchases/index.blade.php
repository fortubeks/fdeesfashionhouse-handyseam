@extends('layouts.app', ['activePage' => 'expenses', 'titlePage' => __('Expenses')])

@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-2"> 
        <a href="{{ url('expenses/create') }}" class="btn btn-primary" >Add New Purchase</a>
      </div>
        
      <div class="col-md-10">
        
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card card-plain">
          <div class="card-header">
            <div class="row">
              <div class="col-md-2">
                <h4 class="card-title mt-0">Total: {{formatCurrency($purchases_sum)}} </h4>
              </div>
              
              <div class="col-md-10">
                <form method="get" action="{{ url('filter-purchases') }}">
                  <div class="row">
                    <div class="col-md-3">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <div class="input-group-text"><i class="material-icons">request_quote</i></div>
                        </div>
                        <input type="text" name="search_value" value="@if(isset($search_value)) {{ $search_value }} @endif" class="form-control" placeholder="Enter Purchase Note">
                      </div>
                    </div>
                    <div class="col-md-3">
                      <select class="form-select" name="category_id">
                        <option value="">--All Category--</option>
                        @foreach (getModelList('items') ?? [] as $item)
                        <option @if(isset($item)) {{(( $item_id == $item->id)) ? 'selected' : '' }} @endif value="{{ __($item->id) }}">{{ __($item->description) }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col-md-2">
                      <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text" id="basic-addon1">FROM</span>
                        </div>
                        <input name="from_filter" class="form-control" @if(isset($from)) value="{{$from}}" @else value="{{date('Y-m-d')}}" @endif type="date" required>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text" id="basic-addon1">TO</span>
                        </div>
                        <input name="to_filter" class="form-control" @if(isset($to)) value="{{$to}}" @else value="{{date('Y-m-d')}}" @endif type="date" required>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <button class="btn btn-primary" type="submit">Filter</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
            
            <p class="card-category"> </p>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover">
                <thead class="">
                    <th>Date</th>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Unit Cost</th>
                    <th>Amount</th>
                </thead>
                <tbody>
                  @forelse($purchases ?? [] as $purchase)
                  <tr class="item" data-id="{{__($purchase->id)}}">
                  <td>
                  {{ __($purchase->created_at->format('d-M-Y') ?? 'None') }}
                  </td>
                  <td>
                  {{ __($purchase->item->description) }}
                  </td>
                  <td>
                  {{ __($purchase->item->qty) }}
                  </td>
                  <td>
                  {{ __($purchase->item->unit_cost) }}
                  </td>
                  <td>
                  {{ __(formatCurrency($purchase->amount)) }}
                  </td>
                  </tr>
                  @empty
                  <tr><td>No Purchase found</td></tr>
                  @endforelse
                </tbody>
              </table>
              @if($purchases instanceof \Illuminate\Pagination\LengthAwarePaginator )
                <div class="justify-content-center">{{$purchases->appends(request()->query())->links()}}</div>
                @endif
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
    var url = "{{ url('purchases/') }}/"+id;
    if(id) {
        window.location = url;
    }
})
});

</script>