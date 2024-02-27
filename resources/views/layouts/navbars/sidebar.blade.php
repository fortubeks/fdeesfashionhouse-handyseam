<div class="sidebar" data-color="orange" data-background-color="white" data-image="{{ asset('material') }}/img/sidebar-1.jpg">
  <!--
      Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

      Tip 2: you can also add an image using data-image tag
  -->
  <div class="logo">
    <a href="{{ url('/') }}" class="simple-text logo-normal">
      
      <img  src="{{ asset('/storage/logo_images/'.auth()->user()->user_account->app_settings->business_logo) }}">
    </a>
  </div>
  <div class="sidebar-wrapper">
    <ul class="nav">
      <li class="nav-item{{ $activePage == 'dashboard' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('home') }}">
          <i class="material-icons">dashboard</i>
            <p>{{ __('Dashboard') }}</p>
        </a>
      </li>
      <li class="nav-item {{ $activePage == 'orders' ? ' active' : '' }}">
        <a class="nav-link" data-toggle="collapse" href="#laravelExample" aria-expanded="true">
          <i class="material-icons">shopping_cart</i>
          <p>{{ __('Orders') }}
            <b class="caret"></b>
          </p>
        </a>
        <div class="collapse show" id="laravelExample">
          <ul class="nav">
            <li class="nav-item{{ $activePage == '' ? ' active' : '' }}">
              <a class="nav-link" href="{{ url('orders/create') }}">
                <i class="material-icons">add_circle</i>
                <span class="sidebar-normal">{{ __('Create New Order') }} </span>
              </a>
            </li>
            <li class="nav-item{{ $activePage == 'orders' ? ' active' : '' }}">
              <a class="nav-link" href="{{ url('orders') }}">
                <i class="material-icons">list_alt</i>
                <span class="sidebar-normal"> {{ __('View Orders') }} </span>
              </a>
            </li>
          </ul>
        </div>
      </li>
      <li class="nav-item{{ $activePage == 'customers' ? ' active' : '' }}">
        <a class="nav-link" href="{{ url('customers') }}">
          <i class="material-icons">groups</i>
            <p>{{ __('Customers') }}</p>
        </a>
      </li>
      @if(auth()->user()->user_type != 'tailor')
      
      <li class="nav-item{{ $activePage == 'items' ? ' active' : '' }}">
        <a class="nav-link" href="{{ url('items') }}">
          <i class="material-icons">inventory</i>
          <p>{{ __('Items & Inventory') }}</p>
        </a>
      </li>
      <li class="nav-item{{ $activePage == 'expenses' ? ' active' : '' }}">
        <a class="nav-link" href="{{ url('expenses') }}">
          <i class="material-icons">money</i>
          <p>{{ __('Expenses') }}</p>
        </a>
      </li>
      @endif
      @can('create', '\App\Models\User')
      <li class="nav-item{{ $activePage == 'payments' ? ' active' : '' }}">
        <a class="nav-link" href="{{ url('payments') }}">
          <i class="material-icons">payment</i>
            <p>{{ __('Payments') }}</p>
        </a>
      </li>
      <li class="nav-item{{ $activePage == 'staff' ? ' active' : '' }}">
        <a class="nav-link" href="{{ url('staffs') }}">
          <i class="material-icons">people_alt</i>
          <p>{{ __('Staff') }}</p>
        </a>
      </li>
      <li class="nav-item{{ $activePage == 'sales-report' ? ' active' : '' }}">
        <a class="nav-link text-white bg-success" href="{{ url('sales-report') }}">
          <i class="material-icons text-white">summarize</i>
          <p>{{ __('Sales Report') }}</p>
        </a>
      </li>
      @endcan
      </hr>
      <li class="nav-item">
        <a class="nav-link " href="https://wa.me/2348090839412">
          <i class="material-icons">contact_support</i>
          <p>{{ __('Contact Support') }}</p>
        </a>
      </li>
    </ul>
  </div>
</div>
