@extends('layouts.app', ['activePage' => 'table', 'titlePage' => __('Create Order')])

@section('content')
<div class="content">
  <div class="container-fluid">
  
    <div class="row">
      <div style="margin-left: auto;margin-right: auto;" class="col-md-8">
        <div style="margin-left: auto;margin-right: auto;" class="card text-center" style="width: 20rem;">
            <div class="card-body">
                <h4 class="card-title">Tailoring</h4>
                <p class="card-text">Create order for a customer who wants a tailoring job</p>
                <a href="{{ url('/create-order/tailoring/step1') }}" class="btn btn-primary">Go</a>
            </div>
        </div>
        <div style="margin-left: auto;margin-right: auto;" class="card text-center" style="width: 20rem;">
            <div class="card-body">
                <h4 class="card-title">Sales</h4>
                <p class="card-text">Create order for a customer who wants to buy your ready made clothes or other items for sale</p>
                <a href="{{ url('/create-order/sales/step1') }}" class="btn btn-primary">Go</a>
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
    var url = "{{ url('orders/') }}/"+id;
    if(id) {
        window.location = url;
    }
})
});
</script>