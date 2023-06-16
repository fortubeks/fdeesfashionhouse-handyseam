@extends('layouts.app', ['activePage' => 'settings', 'titlePage' => __('Settings')])
@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card card-plain">
          <div class="card-header card-header-primary">
            <h4 class="card-title mt-0">{{ __('Configure Your Measurement Details') }}</h4>
            <p class="card-category"> </p>
          </div>
            <div class="alert alert-warning">
            {{ __('Add or Remove Measurement Details necessary for taking your customer measurements. You will only have to do this once') }}
            </div>
          <div class="card-body">
            <div class="">
                <form action="{{ url('/setup/measurements') }}" method="post" accept-charset="UTF-8" >
                @csrf
                <div class="col-md-6">
                  @php
                  $user_measurement_details = json_decode(auth()->user()->app_settings->measurement_details, true);
                  @endphp
                  @foreach($user_measurement_details as $key => $measurement_detail)
                      <div id="{{ __($key) }}" class="input-group control-group" >
                          <input type="text"  name="measurement_details[]" class="form-control mb-2" value="{{ __($measurement_detail) }}" > 
                          <div class="input-group-btn"> 
                          <button type="button" data-id="{{$key}}" class="btn btn-danger delete"><i class="glyphicon glyphicon-remove"></i> Remove</button>
                          </div>
                      </div>
                                          
                  @endforeach
                    <div class="input-group control-group increment" >
                      <input id="new_entry" type="text" class="form-control mb-2">
                      <div class="input-group-btn"> 
                          <button data-id="new_entry" class="btn btn-success" type="button"><i class="glyphicon glyphicon-plus"></i>Add</button>
                      </div>
                    </div>
                        
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">Continue</button>
                </div>
                
                </form>
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
    $(".btn-success").click(function(){ 
          //var html = $(".clone").html();
          var id = $(this).attr("data-id");
          var newEntry = $("#"+id).val();
          var html = '<div id="'+newEntry+'" class="control-group input-group" style="margin-top:10px">';
              html += '<input value="'+newEntry+'" type="text" name="measurement_details[]" class="form-control mb-2">';
              html += '<div class="input-group-btn">';
              html += '<button data-id="'+newEntry+'" class="btn btn-danger" type="button"><i class="glyphicon glyphicon-remove"></i> Remove</button>';
              html += '</div></div>';                 
          $(".increment").before(html);
          $("#"+id).val("");
          $("#"+id).focus();
      });

      $("body").on("click",".btn-danger",function(){
        var id = $(this).attr("data-id");
        $("#"+id).remove();
          //$(this).parents(".control-group").remove();
      });
});
</script>