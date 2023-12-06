@extends('layouts.app', ['activePage' => 'orders', 'titlePage' => __('Orders')])

@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      
      <div class="col-md-10 mb-3">
        <form action="{{ url('/items-search') }}" method="get">
          <div class="row">
            <div class="col-md-6">
              <div class="input-group">
                <div class="input-group-prepend">
                  <div class="input-group-text"><i class="material-icons">inventory</i></div>
                </div>
                <input type="text" name="search_value" class="form-control" placeholder="Enter Item Name" >
              </div>
            </div>
            <div class="col-md-3">

              <select class="form-select" name="search_by" aria-label=".form-select-sm example">
                    <option value="desc">Search By Item Description</option>
                    <option value="sku">Search By SKU</option>
              </select>
            </div>
            <div class="col-md-3">
              <button class="btn btn-primary btn-block" type="submit" id="">Search</button>
            </div>  
          </div>
        </form>
      </div>
    </div>
    <div class="row">
      <div class="col-md-8">
        <div class="card card-plain">
          <div class="card-header card-header-primary">
            <h4 class="card-title mt-0"> Result/List</h4>
            <p class="card-category"> </p>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover">
                <thead class="">
                    <th>Category</th>
                    <th>Item Name</th>
                    <th>Qty in Stock</th>
                    <th>Unit Price</th>
                    <th></th>
                </thead>
                <tbody>
                  @if(count($items)<=0)
                    <tr><td>No Items for sale found.  <a class="btn" href="{{ url('/items/create') }}">Add Items</a> </td></tr>
                    @endif
                  @foreach($items ?? [] as $item)
                  <tr class="item" data-id="{{__($item->id)}}">
                  <td>
                  {{ __($item->category->name ?? 'None') }}
                  </td>
                  <td>
                  {{ __($item->description) }}
                  </td>
                  <td>
                  {{ __($item->inventory_quantity) }}
                  </td>
                  <td>
                  {{ __(formatCurrency($item->price)) }}
                  </td>
                  <td>
                      @if($item->inventory_quantity > 0)
                        <button type="button" data-id="{{$item->id}}" data-price="{{$item->price}}" class="btn btn-success addToCart">Add To Basket</button>
                        
                        @endif
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
                                     
                    
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
      <div class="card card-plain">
          <div class="card-header card-header-primary">
            <h4 class="card-title mt-0"> {{ __('Item Basket For '.$customer->name ?? 'None') }}</h4>
            <p class="card-category"> </p>
          </div>
          <div class="card-body">
          <form action="{{ url('orders/') }}" method="post">
                    @csrf
                    <input type="hidden" name="order_type" value="sales">
                    <input type="hidden" name="customer_id" value="{{ __($customer->id) }}">
                    
                    <div id="cart">
                        <?php 
                        //$cart_items = [];
                        $cart_items = session("cart_items") ?? [];
                        ?>
                        @if(count($cart_items)>0)
                            @foreach($cart_items ?? [] as $item)
                                <p>{{ __($item->description ?? 'None') }}&nbsp;&nbsp;&nbsp;{{ __($item->price ?? 'None') }}<a data-id='{{ __($item->id) }}' data-price='{{ __($item->price) }}' onclick='removeFromCart(this)' class='item removeFromCart'><i class='material-icons'>clear</i></button></p>
                            @endforeach
                            
                            
                            <input type="hidden" name="total_amount" value="{{ __(session('cart_total_amount')) }}">
                            <p>
                              <label>Total Amount: NGN  {{ __(session('cart_total_amount')) }}</label>
                            </p>
                            <p class="mt-3">
                            <label class="bmd-label-floating">Date of Order</label>
                            <input placeholder="Date of Order" onfocus="(this.type='date')" value="{{now()}}" name="created_at" type="text" class="form-control" required>
                            
                            </p>
                            <p class="mt-2"><button type="submit" class='btn btn-primary' type='button'>Create Order & Proceed To Payment</button></p>
                            
                        @endif
                        
                    </div>
                    </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
<script>
window.addEventListener('load', function() {
    $(".addToCart").on("click", function(){
    
    var id = $(this).attr("data-id");
    var total_amount = Number($("#total_amount").val());
    total_amount += Number($(this).attr("data-price"));
    let nairaNGALocale = Intl.NumberFormat('en-US');
    //alert (id);
    $.ajax({
        url: "{{ url('/orders/addToCart/') }}/"+id,
        type: "GET",
        data: {itemID: id},
        success: function(data) {
            // Do stuff when the AJAX call returns
            //like refresh the basket
            $("#cart").html("");
            //console.log(data);
            //var data=$.parseJSON(data);
            jQuery.each(data, function(index, item){
                //$("#cart").append("<p>"+item.description+"<button type='button' data-id='"+item.id+"' data-price='"+item.price+"' onclick='removeFromCart("+item.id+")' class='btn removeFromCart'><i class='bi bi-x-square-fill'></i></button></p>");
                $("#cart").append("<p>"+item.description+"&nbsp;&nbsp;&nbsp;"+item.price+"&nbsp;&nbsp;&nbsp;<a data-id='"+item.id+"' data-price='"+item.price+"' onclick='removeFromCart(this)' class='item removeFromCart'><i class='material-icons'>clear</i></a></p>");
            });
            $("#cart").append("<p>Total NGN "+nairaNGALocale.format(total_amount)+"</p>");
            
            $("#cart").append("<input type='hidden' id='total_amount' name='total_amount' value='"+total_amount+"'>");
            $("#cart").append("<p><button type='submit' class='btn btn-primary' type='button'>Create Order & Proceed To Payment</button></p>");
            
        }
    });                           
    
});
});

function removeFromCart(itemObj){
    var id = itemObj.getAttribute("data-id");
    var total_amount = Number($("#total_amount").val());
    total_amount -= Number(itemObj.getAttribute("data-price"));
    let nairaNGALocale = Intl.NumberFormat('en-US');
    //alert (id);
    $.ajax({
        url: "{{ url('/orders/removeFromCart/') }}/"+id,
        type: "GET",
        data: {itemID: id},
        success: function(data) {
            // Do stuff when the AJAX call returns
            //like refresh the basket
            $("#cart").html("");
            //console.log(data);
            //var data=$.parseJSON(data);
            jQuery.each(data, function(index, item){
                //$("#cart").append("<p>"+item.description+"<button type='button' data-id='"+item.id+"' data-price='"+item.price+"' onclick='removeFromCart("+item.id+")' class='btn removeFromCart'><i class='bi bi-x-square-fill'></i></button></p>");
                $("#cart").append("<p>"+item.description+"&nbsp;&nbsp;&nbsp;"+item.price+"&nbsp;&nbsp;&nbsp;<a data-id='"+item.id+"' data-price='"+item.price+"' onclick='removeFromCart(this)' class='item removeFromCart'><i class='material-icons'>clear</i></a></p>");
            });
            $("#cart").append("<p>Total NGN "+nairaNGALocale.format(total_amount)+"</p>");

            $("#cart").append("<input type='hidden' id='total_amount' name='total_amount' value='"+total_amount+"'>");
            $("#cart").append("<p><button type='submit' class='btn btn-primary' type='button'>Create Order & Proceed To Payment</button></p>");
        }
    })
}
</script>