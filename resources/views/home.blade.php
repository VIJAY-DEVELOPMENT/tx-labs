@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="row">
                    @foreach ($products as $product)
                        <div class="col-md-4 ">
                            <div class="card">
                                <img src="{{ asset($product->product_image) }}" class="card-img-top img-product" alt="...">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-text">{{ $product->product_name }}</h6>
                                            <h5 class="card-title mb-0">₹ {{ $product->price }}</h5>
                                        </div>
                                        <div>
                                            <a href="/add-to-cart" data-url="{{ route('add.to.cart',['product_id' => $product->id]) }}" class="btn btn-primary add-to-cart">Add to Cart</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @empty($products)
                    <p class="text-center">No product found.</p>
                @endempty
            </div>
        </div>
    </div>
@endsection
