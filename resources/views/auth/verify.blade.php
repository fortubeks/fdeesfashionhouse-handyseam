@extends('layouts.app', ['class' => 'off-canvas-sidebar', 'activePage' => 'home', 'titlePage' => __(' Welcome To HandySeam')])

@section('content')
<div class="container" style="height: auto;position: absolute; top: 90px;">
  <div class="row justify-content-center">
      <div class="col-lg-7 col-md-8">
          <div class="card card-login card-hidden mb-3">
            <div class="card-header card-header-primary text-center">
              <p class="card-title"><strong>{{ __('Verify Your Email Address') }}</strong></p>
            </div>
            <div class="card-body">
              @if (session('resent'))
                    <div class="alert alert-success" role="alert">
                        {{ __('A fresh verification link has been sent to your email address.') }}
                    </div>
              @else
              <p class=" text-center">{{ __('We need you to verify your email which helps us keep you safe') }}</p>
              <p class=" text-center">{{ __('Please check your email for a verification link') }}</p>
              <p class=" text-center">{{ __('If you cannot find it in your inbox, please check your spam box.') }}</p>
              @endif
              <p class="card-description text-center">
                @if (Route::has('verification.resend'))
                    {{ __('If you did not receive the email') }},  
                    <form class="text-center" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.
                    </form>
                @endif
              </p>
            </div>
          </div>
      </div>
  </div>
</div>
@endsection
