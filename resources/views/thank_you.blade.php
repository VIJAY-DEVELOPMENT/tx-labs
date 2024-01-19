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
                        Thank you for your order (#{{ $order_details->order_no }}).
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script></script>
@endsection
