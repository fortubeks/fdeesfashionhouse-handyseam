@extends('layouts.app', ['activePage' => 'expenses', 'titlePage' => __('Expense Categories')])

@section('content')
<div class="content">
  <div class="container-fluid">
  <form action="{{ url('/expense-categories/'.$expense_category->id) }}" method="post">
        @csrf
    <div class="row">
    
      <div class="col-md-4 mb-3"> 
        <input type="text" name="description" value="{{ __($expense_category->name ?? 'None') }}" class="form-control" placeholder="Enter Item Description" aria-label="Enter Item Description"> 
      </div>
        
      <div class="col-md-4 mb-3">
          <button class="btn btn-primary btn-block" type="submit" id="">Update</button>
          <input type="hidden" name="_method" value="PUT">
      </div>
      <div class="col-md-4 mb-3">
        <button class="btn btn-secondary btn-delete" type="button" id="">Delete</button>
      </div>
      
    </div>
  </form>
    
  </div>
</div>
<form id="form-delete" onsubmit="return confirm('Are you sure you want to delete this category?');" action="{{ url('/expense-categories/'.$expense_category->id) }}" method="post">
  @csrf
  @method('DELETE')
</form>
@endsection
<script>
window.addEventListener('load', function() {
    $('.btn-delete').click(function() {
    $('#form-delete').submit();
})
});
</script>