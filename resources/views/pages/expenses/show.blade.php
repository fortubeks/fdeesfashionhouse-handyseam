@extends('layouts.app', ['activePage' => 'expenses', 'titlePage' => __('Expenses')])
@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card ">
          <div class="card-header card-header-primary">
            <h4 class="card-title mt-0">{{ __('Expense') }}</h4>
            <p class="card-category">Update Expense </p>
          </div>
            
          <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="card ">
                        <form action="{{ url('expenses/'.$expense->id) }}" method="post">
                            @csrf
                            <div class="card-body ">
                                <div class="form-group bmd-form-group mb-4">
                                    <label>Select Category</label>
                                    <select class="form-select form-control" data-category="{{ __($expense->category_id ?? 'None') }}" name="category_id" id="category">
                                        @foreach ($expense_categories ?? [] as $expense_category)
                                        <option value="{{ __($expense_category->id) }}">{{ __($expense_category->name) }}</option>
                                        @endforeach
                                    </select> 
                                </div>
                                <div class="form-group bmd-form-group mb-4">
                                    <label>Name/Description</label>
                                    <input type="text" name="description" class="form-control" value="{{ __($expense->description ?? 'None') }}" aria-label="Enter Item Description">
                                </div>
                                <div class="form-group bmd-form-group mb-4">
                                    <label>Amount</label>
                                    <input type="number" name="amount" class="form-control" step=".001" min="0" value="{{ __($expense->amount ?? 'None') }}" aria-label="Enter Cost Price">
                                </div> 
                                <div class="form-group bmd-form-group mb-4">
                                    <label>Date of Expense</label>
                                    <input type="date" name="value_date" class="form-control" value="{{ __($expense->value_date ?? 'None') }}" > 
                                </div> 
                                
                            </div>
                            <div class="card-footer ">
                                <input type="hidden" name="_method" value="PUT">
                                <button type="submit" class="btn btn-primary">Update</button>  
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
    var category = $('#category').attr("data-category");
    $('#category option[value="'+category+'"]').attr('selected','selected');
});
</script>
