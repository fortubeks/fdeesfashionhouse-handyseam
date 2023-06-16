@extends('layouts.app', ['activePage' => 'expenses', 'titlePage' => __('Expense Categories')])

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Add New Expense Category') }}</div>

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
                    <form action="{{ url('expense-categories/') }}" method="post">
                    @csrf
                        
                        <div class="form-group">
                            <label>Name/Description</label>
                            <input type="text" name="description" class="form-control" placeholder="Enter Description" aria-label="Enter Item Description">  
                        </div>
                        

                        <button type="submit" class="btn btn-primary">Create</button>  
                        </div>
                    </form>
                </div>

            </div>
            
            
            
        </div>
    </div>
</div>
@endsection
