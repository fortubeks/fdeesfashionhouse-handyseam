@extends('layouts.app', ['activePage' => 'upgade', 'titlePage' => __('Upgrade to Premium')])

@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-8 ml-auto mr-auto">
        <div class="card">
          <div class="card-header card-header-primary">
            <h4 class="card-title">HandySeam Premium</h4>
            <p class="card-category">Here is our Premium Version of HandySeam.</p>
          </div>
          <div class="card-body">
            <div class="table-responsive table-upgrade">
              <table class="table">
                <thead>
                  <tr>
                    <th></th>
                    <th class="text-center">Free</th>
                    <th class="text-center">Premium</th>
                  </tr>
                </thead>
                <tbody>
                  
                  <tr>
                    <td>Customer Information & Order History</td>
                    <td class="text-center"><i class="fa fa-check text-success"></i></td>
                    <td class="text-center"><i class="fa fa-check text-success"></i></td>
                  </tr>
                  <tr>
                    <td>Customer Measurements</td>
                    <td class="text-center"><i class="fa fa-check text-success"></i></td>
                    <td class="text-center"><i class="fa fa-check text-success"></i></td>
                  </tr>
                  <tr>
                    <td>Sales & Orders Managment</td>
                    <td class="text-center"><i class="fa fa-check text-success"></i></td>
                    <td class="text-center"><i class="fa fa-check text-success"></i></td>
                  </tr>
                  <tr>
                    <td>Styles & Images Upload</td>
                    <td class="text-center"><i class="fa fa-check text-success"></i></td>
                    <td class="text-center"><i class="fa fa-check text-success"></i></td>
                  </tr>
                  <tr>
                    <td>Customer Invoicing</td>
                    <td class="text-center"><i class="fa fa-check text-success"></i></td>
                    <td class="text-center"><i class="fa fa-check text-success"></i></td>
                  </tr>
                  <tr>
                    <td>Items & Inventory Management</td>
                    <td class="text-center"><i class="fa fa-check text-success"></i></td>
                    <td class="text-center"><i class="fa fa-check text-success"></i></td>
                  </tr>
                  <tr>
                    <td>Staff & Tailors Management</td>
                    <td class="text-center"><i class="fa fa-times text-danger"></i></td>
                    <td class="text-center"><i class="fa fa-check text-success"></i></td>
                  </tr>
                  <tr>
                    <td>Shop Expenses Management</td>
                    <td class="text-center"><i class="fa fa-times text-danger"></i></td>
                    <td class="text-center"><i class="fa fa-check text-success"></i></td>
                  </tr>
                  <tr>
                    <td>Financial Management</td>
                    <td class="text-center"><i class="fa fa-times text-danger"></i></td>
                    <td class="text-center"><i class="fa fa-check text-success"></i></td>
                  </tr>
                  <tr>
                    <td>Marketing: WhatsApp</td>
                    <td class="text-center"><i class="fa fa-times text-danger"></i></td>
                    <td class="text-center"><i class="fa fa-check text-success"></i></td>
                  </tr>
                  <tr>
                    <td>Marketing: Bulk SMS</td>
                    <td class="text-center"><i class="fa fa-times text-danger"></i></td>
                    <td class="text-center"><i class="fa fa-check text-success"></i></td>
                  </tr>
                  <tr>
                    <td>Premium Support</td>
                    <td class="text-center"><i class="fa fa-times text-danger"></i></td>
                    <td class="text-center"><i class="fa fa-check text-success"></i></td>
                  </tr>
                  <tr>
                    <td></td>
                    <td class="text-center">Free</td>
                    <td class="text-center">Just ₦2,500 -/month ($3)</td>
                  </tr>
                  <tr>
                    @if(!auth()->user()->user_account->isPremiumUser())
                    <td class="text-center"></td>
                    <td class="text-center">
                      <a href="#" class="btn btn-round btn-fill btn-default disabled">Current Version</a>
                    </td>
                    <td class="text-center">
                      <form>
                        <script src="https://js.paystack.co/v1/inline.js"></script>
                        <button type="button" onclick="payWithPaystack()" class="btn btn-round btn-fill btn-info"> Upgrade To Premium </button> 
                      </form>
                    </td>
                    @else
                    <td class="text-center"></td>
                    <td class="text-center">
                      
                    </td>
                    <td class="text-center">
                      <a href="#" class="btn btn-round btn-fill btn-default disabled">Current Version</a>
                    </td>
                    @endif
                  </tr>
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
  function payWithPaystack(){
    var handler = PaystackPop.setup({
      key: 'pk_live_a7ffaee75929ee13ad1d2538cbd2309793c61f53',
      email: '<?php echo auth()->user()->user_account->email ?>',
      amount: 2500,
      plan: "PLN_omuwrdl8m9i8mkd",
      ref: ''+Math.floor((Math.random() * 1000000000) + 1), // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
      metadata: {
         custom_fields: [
            {
                display_name: "Mobile Number",
                variable_name: "mobile_number",
                value: "+2348012345678"
            }
         ]
      },
      callback: function(response){
          //alert('success. transaction ref is ' + response.reference);
          
          url = "{{ url('/verify-subscription-payment') }}/"+response.reference;
          window.location = url;

          
      },
      onClose: function(){
          //alert('window closed');
      }
    });
    handler.openIframe();
  }
</script>
