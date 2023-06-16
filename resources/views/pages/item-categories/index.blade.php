@extends('layouts.app', ['activePage' => 'items', 'titlePage' => __('Items')])

@section('content')
<div class="content">
  <div class="container-fluid">
    <form action="{{ url('/item-categories') }}" method="post">
        @csrf
    <div class="row">
      <div class="col-md-4 mb-3"> 
        <input type="text" required name="description" value="" class="form-control" placeholder="Enter New Item Category" aria-label="Enter Item Description">
      </div>
        
      <div class="col-md-4 mb-3">
          <button class="btn btn-primary btn-block" type="submit" id="">Create</button>
        
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
                    <th>Category Name</th>
                    <th>Number of Items</th>
                </thead>
                <tbody>
                  @if(count($item_categories)<=0)
                    <tr><td>No Categories found</td></tr>
                    @endif
                  @foreach($item_categories ?? [] as $item_category)
                  <tr class="item" data-id="{{__($item_category->id)}}">
                  <td>
                  {{ __($item_category->name ?? 'None') }}
                  </td>
                  <td>
                  {{ __(count($item_category->items)) }}
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
    var url = "{{ url('item-categories/') }}/"+id;
    if(id) {
        window.location = url;
    }
})
});
</script>