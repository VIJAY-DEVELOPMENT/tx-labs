<div class="row">
    <div class="col-md-6">
        <p><strong>Order Number : </strong><br>{{ $order->order_no }}</p>
    </div>
    <div class="col-md-6">
        <p><strong>Order Date : </strong><br>{{ date("d M, Y",strtotime($order->created_at)) }}</p>
    </div>
    <div class="col-md-6">
        <p><strong>User Name : </strong><br>{{ $order->user->name }}</p>
    </div>
    <div class="col-md-6">
        <p><strong>User Email : </strong><br>{{ $order->user->email }}</p>
    </div>

    <div class="col-md-12">
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
                @foreach ($order->order_details as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->product->product_name }}</td>
                        <td>{{ $item->qty }}</td>
                        <td>{{ $subtotal = $item->product->price * $item->qty }}</td>
                        <?php
                        $total = $total + $subtotal;
                        ?>
                    </tr>
                @endforeach
            </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-right">Total</th>
                        <th>{{ $total }}</th>
                    </tr>
                </tfoot>
        </table>
    </div>
</div>