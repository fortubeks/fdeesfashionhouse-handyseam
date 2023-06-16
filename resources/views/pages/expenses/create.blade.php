@extends('layouts.app', ['activePage' => 'expenses', 'titlePage' => __('Expenses')])
@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card ">
          <div class="card-header card-header-primary">
            <h4 class="card-title mt-0">{{ __('Add New Expense') }}</h4>
            <p class="card-category"> </p>
          </div>
            
          <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="card ">
                        <form action="{{ url('expenses/') }}" method="post">
                            @csrf
                            <div class="card-body ">
                                <div class="form-group bmd-form-group mb-4">
                                    <label>Select Category</label>
                                    <select class="form-select form-control" name="category_id">
                                        @foreach (getModelList('expense-categories') as $expense_category)
                                        <option value="{{ __($expense_category->id) }}">{{ __($expense_category->name) }}</option>
                                        @endforeach
                                    </select> 
                                </div>
                                <div class="form-group bmd-form-group mb-4">
                                    <label>Name/Description</label>
                                    <input type="text" name="description" required class="form-control" placeholder="Enter Item Description" aria-label="Enter Item Description">
                                </div>
                                <div class="form-group bmd-form-group mb-4">
                                    <label>Amount</label>
                                    <input type="number" name="amount" step=".001" required min="0" class="form-control" placeholder="Enter Amount" aria-label="Enter Cost Price">
                                </div> 
                                <div class="form-group bmd-form-group mb-4">
                                    <label>Date of Expense</label>
                                    <input type="date" name="value_date" required class="form-control" placeholder="" > 
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
