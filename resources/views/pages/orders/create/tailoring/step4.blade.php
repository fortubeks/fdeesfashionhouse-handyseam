@extends('layouts.app', ['activePage' => 'orders', 'titlePage' => __('Orders')])
@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card ">
          <div class="card-header card-header-primary">
            <h4 class="card-title mt-0">{{ __('Create Invoice') }}</h4>
            <p class="card-category"> </p>
          </div>
            
          <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="card ">
                        <form action="{{ url('orders/') }}" method="post">
                            @csrf
                            <div class="card-body ">
                                <div class="row">
                                    <div class="col-md-12 mb-4">
                                        <label class="bmd-label-floating">Customer Name</label>
                                        <input type="text" readonly class="form-control" value="{{ __($customer->name ?? 'None') }}">
                                        <input type="hidden" name="customer_id" class="form-control" value="{{ __($customer->id ?? 'None') }}" >
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <label for="" class="bmd-label-floating">Amount Charged</label>
                                        <input type="number" name="total_amount" value="{{ __($total_amount) }}" class="form-control" required readonly>
                                    </div>
                                    <div class="col-md-12">
                                        <input placeholder="Expected Date Of Delivery" onfocus="(this.type='date')" name="expected_delivery_date" type="text" class="form-control" required>
                                    </div> 
                                </div> 
                            </div>
                            <div class="card-footer ">
                                <input type="hidden" name="order_type" class="form-control" value="{{ __('tailoring') }}" >
                                <button type="submit" class="btn btn-fill btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-6">
                    <table id="datatables" class="table">
                        <thead class="text-primary">
                            <tr role="row">
                                <th class="desktop " style="width: 194px;">
                                Photo
                                </th>
                                <th class="desktop " style="width: 93px; " >
                                    Style Description
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $outfits = session('outfit_orders'); 
                            ?>
                            @foreach($outfits as $key => $outfit)
                            <?php 
                            $titles = explode('<?>', $outfit);
                            $url = asset('/storage/styles/'.$titles[4]);
                            ?>
                            
                            <tr role="row">
                                <td tabindex="0" class="sorting_1">
                                    <div class="avatar avatar-sm rounded-circle img-circle" style="width:100px; height:100px;overflow: hidden;">
                                        <img src="{{ $url }}" alt="" style="max-width: 100px;">
                                    </div>
                                </td>
                                <td>
                                    {{__($titles[0])}}
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
</div>
@endsection

<script>
window.addEventListener('load', function() {
    $(".btn-success").click(function(){ 
          var html = $(".clone").html();
          $(".increment").after(html);
      });

      $("body").on("click",".btn-danger",function(){ 
          $(this).parents(".control-group").remove();
      });
});
</script>
