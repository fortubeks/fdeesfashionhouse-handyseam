@extends('layouts.app', ['activePage' => 'customers', 'titlePage' => __('Customer')])
@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card ">
          <div class="card-header card-header-primary">
            <h4 class="card-title mt-0">{{ __('Import Customers') }}</h4>
            <p class="card-category"> </p>
          </div>
            
          <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="card ">
                        <p><a href="{{route('download.sample.excel')}}">Download sample file</a></p>
                        <form action="{{ route('import.customers') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="file" required>
                            <button type="submit" class="btn btn-primary">Import Customers</button>
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
