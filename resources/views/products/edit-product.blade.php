@extends('layouts.app')

@section('content')

    
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if (session()->has('update'))
                    <div class="alert alert-success">
                        {{ session('update') }}
                    </div>
                @endif

                @if (session()->has('delete'))
                    <div class="alert alert-danger">
                        {{ session('delete') }}
                    </div>
                @endif
                <div class="card">
                    <div class="card-header">{{ __('Edit Product') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('product.update',['product'=>$product->id]) }}">
                            @csrf
                            @method("PATCH")

                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                                <div class="col-md-6">
                                    <input id="name" value="{{ $product->name }}" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="price" class="col-md-4 col-form-label text-md-right">{{ __('price') }}</label>

                                <div class="col-md-6">
                                    <input id="price" value="{{ $product->price }}" type="number" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old('price') }}" required autocomplete="price" autofocus>

                                    @error('price')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="form-group row mb-0">
                                <div class="col-md-6 text-right">
                                    @if ( auth()->user()->role == "Admin" )
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('Update') }}
                                        </button>
                                    @endif
                                    
                                </div>
                                <div class="col-md-4 text-right">
                                    <a href="{{ route("product.show",['product'=>$product->id]) }}" class="btn btn-primary">Edit Attribute</a>
                                </div>
                            </div>
                        </form>

                        <h4 class="mt-5">Product Attributes</h4>
                        <table class="table table-striped table-bordered table-hover">
                            <tr>
                                <th>Name</th>
                                <th>Quantity</th>
                            </tr>
                            @foreach ($product->attributes as $attributes)
                            <tr>
                                <td>{{ $attributes->value }}</td>
                                <td>{{ $attributes->quantity }}</td>
                            </tr>    
                            @endforeach
                            

                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection