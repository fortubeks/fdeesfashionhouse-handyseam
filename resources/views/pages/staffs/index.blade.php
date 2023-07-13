@extends('layouts.app', ['activePage' => 'staff', 'titlePage' => __('Staffs')])

@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-2 mb-3"> 
        <a href="{{ url('staffs/create') }}" class="btn btn-primary" >Add New Staff</a>
      </div>
        
      <div class="col-md-8 mb-3">
        <form action="{{ url('/admin-staffs-search') }}" method="get">
            @csrf
          <div class="row">
            <div class="col-md-8">
                <input type="hidden" name="search_by" value="name"/>
                <input type="text" name="name" class="form-control" placeholder="Enter First Name" >
            </div>
            <div class="col-md-4">
              <button class="btn btn-primary btn-block " type="submit" id="">Search</button>
            </div>  
          </div>
        </form>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card card-plain">
          <div class="card-header card-header-primary">
            <h4 class="card-title mt-0"> All Staff</h4>
            <p class="card-category"> View All Staff</p>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover">
                <thead class="">
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Phone</th>
                    <th>Role</th>
                </thead>
                <tbody>
                  @if(count($staffs)<=0)
                    <tr><td>No Staff found</td></tr>
                    @endif
                  @foreach($staffs ?? [] as $staff)
                  <tr class="item" data-id="{{__($staff->id)}}">
                  <td>
                  {{ __( $staff->first_name) }}
                  </td>
                  <td>
                      {{ __($staff->last_name) }}
                  </td>
                  <td>
                  {{ __($staff->phone) }}
                  </td>
                  <td>
                  {{ __($staff->role) }}
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
    var url = "{{ url('staffs/') }}/"+id;
    if(id) {
        window.location = url;
    }
})
});
</script>