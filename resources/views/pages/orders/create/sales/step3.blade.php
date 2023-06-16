@extends('layouts.app', ['activePage' => 'table', 'titlePage' => __('Create Order')])
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Add Style For New Order') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ url('/create-order/tailoring/addstyle') }}" method="get">
                    @csrf
                        <div class="form-group">
                        <textarea id="style" name="style" rows="4" class="form-control" value="" placeholder="Add Style ..."></textarea>  
                        </div>

                        <button type="submit" class="btn btn-outline-success">Continue</button>  
                        </div>
                    </form>
                </div>

            </div>
            
            
            
        </div>
    </div>
</div>
@endsection
