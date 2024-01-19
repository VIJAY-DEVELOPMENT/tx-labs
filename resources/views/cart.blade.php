@extends('layouts.app')
@section('css')
@endsection
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h4 class="mb-0">{{ isset($title) ? $title : '' }}</h4>
                    </div>

                    <div class="card-body">
                        <table class="table table-bordered data-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $total = 0;
                                ?>
                                @foreach ($items as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item['product']['product_name'] }}</td>
                                        <td>{{ $item['qty'] }}</td>
                                        <td>{{ $subtotal = $item['product']['price'] * $item['qty'] }}</td>
                                        <?php
                                        $total = $total + $subtotal;
                                        ?>
                                    </tr>
                                @endforeach

                                @empty($items)
                                    <tr>
                                        <td colspan="4" class="text-center">No product found<br /><a
                                                class="btn btn-primary mt-3" href="{{ route('home') }}">Continue Shopping</a>
                                        </td>
                                    </tr>
                                @endempty
                            </tbody>
                            @if (!empty($items))
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-right">Total</th>
                                        <th>{{ $total }}</th>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>

                        @if (!empty($items))
                            @if (Auth::check())
                                <div class="row">
                                    <div class="col-md-12 text-end">
                                        <a class="btn btn-primary place-order" href="{{ route('place.order') }}">Place
                                            Order</a>
                                    </div>
                                </div>
                            @else
                                <div class="row">
                                    <div class="col-md-12 text-end">
                                        <a class="btn btn-primary" href="{{ route('login') }}">Secure Login Required for
                                            Order</a>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).on("click", ".place-order", async function(e) {
            e.preventDefault();
            var data = await ajaxDynamicMethod(
                $(this).attr("href"),
                "GET"
            );
        })
    </script>
@endsection
