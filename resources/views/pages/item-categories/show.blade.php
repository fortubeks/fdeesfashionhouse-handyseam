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
    
  </div>
</div>
@endsection
<script>
window.addEventListener('load', function() {
    $('.item').click(function() {
    var id = $(this).attr("data-id");
    var url = "{{ url('item-categories/') }}/"+id;
    if(id) {
        window.location = url;
    }
})
});
</script>