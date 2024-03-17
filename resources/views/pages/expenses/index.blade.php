@extends('layouts.app', ['activePage' => 'expenses', 'titlePage' => __('Expenses')])

@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-2"> 
        <a href="{{ url('expenses/create') }}" class="btn btn-primary" >Add New Expense</a>
      </div>
        
      <div class="col-md-10">
        <form action="{{ url('/expenses-search') }}" method="get">
          <div class="row">
            <div class="col-md-4">
              
            </div>
            <div class="col-md-3">
            </div>
            <div class="col-md-3">
              <a href="{{ url('weekly-outfit-payments/') }}" class="btn btn-primary btn-block">Tailor Payments</a>
            </div>
            <div class="col-md-2">
              <a href="{{ url('expense-categories/') }}" class="btn btn-link btn-block">Manage Categories</a>
            </div>  
          </div>
        </form>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card card-plain">
          <div class="card-header">
            <div class="row">
              <div class="col-md-2">
                <h4 class="card-title mt-0">Total: {{formatCurrency($expenses_sum)}} ({{$expenses_count}})</h4>
              </div>
              
              <div class="col-md-10">
                <form method="get" action="{{ url('filter-expenses') }}">
                  <div class="row">
                    <div class="col-md-3">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <div class="input-group-text"><i class="material-icons">request_quote</i></div>
                        </div>
                        <input type="text" name="search_value" value="@if(isset($search_value)) {{ $search_value }} @endif" class="form-control" placeholder="Enter Expense Description">
                      </div>
                    </div>
                    <div class="col-md-3">
                      <select class="form-select" name="category_id">
                        <option value="">--All Category--</option>
                        @foreach (getModelList('expense-categories') ?? [] as $expense_category)
                        <option @if(isset($category_id)) {{(( $category_id == $expense_category->id)) ? 'selected' : '' }} @endif value="{{ __($expense_category->id) }}">{{ __($expense_category->name) }}</option>
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
                    <th>Category</th>
                    <th>Description</th>
                    <th>Amount</th>
                </thead>
                <tbody>
                  @if(count($expenses)<=0)
                    <tr><td>No Expenses found</td></tr>
                    @endif
                  @foreach($expenses ?? [] as $expense)
                  <tr class="item" data-id="{{__($expense->id)}}">
                  <td>
                  {{ __($expense->value_date ?? 'None') }}
                  </td>
                  <td>
                  {{ __($expense->category_details()->name) }}
                  </td>
                  <td>
                  {{ __($expense->description) }}
                  </td>
                  <td>
                  {{ __(formatCurrency($expense->amount)) }}
                  </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
              @if($expenses instanceof \Illuminate\Pagination\LengthAwarePaginator )
                <div class="justify-content-center">{{$expenses->appends(request()->query())->links()}}</div>
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
    var url = "{{ url('expenses/') }}/"+id;
    if(id) {
        window.location = url;
    }
})
});
</script>