@extends('layouts.app', ['activePage' => 'orders', 'titlePage' => __('Orders')])
@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card card-plain">
          <div class="card-header card-header-primary">
            <h4 class="card-title mt-0">{{ __('Add Outfits For New Order') }}</h4>
            <p class="card-category"> </p>
          </div>
            <div class="alert alert-warning">
            {{ __('Add outfit description with their pictures below. Add as many as you want. Leave picture empty if there is no picture') }}
            </div>
          <div class="card-body">
            <div class="">
                <form action="{{ url('/create-order/tailoring/addstyle') }}" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
                @csrf
                <div class="col-md-6 mb-3">
                    <h6 class="card-title">Add Individual Outfits & their Images Here </h6>
                    <div id="0" class="" style="margin-top:10px;border:1px solid grey;padding:15px;">
                        <p><input required placeholder="Write the name of the style" focus type="text" name="style_names[]" class="form-control"></p>
                        <p><input required placeholder="Price charged" step=".001" min="0" type="number" name="price[]" class="form-control"></p>
                        <p><input  placeholder="Quantity (optional)" type="number" name="qty[]" class="form-control"></p>
                        <p><textarea class="form-control" placeholder="Instruction (optional)" name="instruction[]"></textarea></p>
                        <p><input type="file" name="styles[]" class="form-control">
                        <button class="mt-3 delete" type="button" data-id="0"><i class="material-icons">delete</i></button></p>
                    </div>
                    <div class="increment" ></div>
                    <div class="input-group control-group " >
                        
                        <div class="input-group-btn mt-3"> 
                            <button data-id="new_entry" class="btn btn-success" type="button"><i class="glyphicon glyphicon-plus"></i>Add Another Outfit</button>
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
function setCustomerID(){
    var value = $('#customer').val();
    $('#customer_id').val($('#customerdatalistOptions [value="' + value + '"]').data('value'));
}
window.addEventListener('load', function() {
    var element_id = 0;
      $(".btn-success").click(function(){ 
          element_id++;
          var id = $(this).attr("data-id");
          //var newEntry = $("#"+id).val();
          var html = '<div id="'+element_id+'" class="" style="margin-top:10px;border:1px solid grey;padding:15px;" >';
              html += '<p><input required placeholder="Write the name of the style" type="text" name="style_names[]" class="form-control mb-2"></p>';
              html += '<p><input required placeholder="Price charged" step=".001" min="0" type="number" name="price[]" class="form-control mb-2"></p>';
              html += '<p><input  placeholder="Quantity (optional)" type="number" name="qty[]" class="form-control"></p>';
              html += '<p><textarea class="form-control" placeholder="Instruction (optional)" name="instruction[]"></textarea></p>';
              html += '<p><input type="file" name="styles[]" class="form-control mb-2"><button class="mt-3 delete" data-id="'+element_id+'" type="button"><i class="material-icons">delete</i> </button></p>';
              html += '</div>';                 
          $(".increment").after(html);
      });

      $("body").on("click",".delete",function(){
        var id = $(this).attr("data-id");
        $("#"+id).remove();
          //$(this).parents(".control-group").remove();
      });
});
</script>