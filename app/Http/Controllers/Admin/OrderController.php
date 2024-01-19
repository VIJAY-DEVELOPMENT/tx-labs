<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Order;
use Illuminate\Http\Request;
use DataTables;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $data = Order::with(['user'])->orderBy('id','DESC');
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->editColumn('order_no', function ($row) {
                        return '<a href="' . route('admin.orders.show', ['order' => $row->id]) . '" data-title="Order View" class="modal-link" title="View">'.$row->order_no.'</a>';
                    })
                    ->rawColumns(['order_no'])
                    ->make(true);
            }

            $title = "Orders";
            return view('admin.orders.index', compact('title'));
        } catch (\Exception $e) {
            return catchReponse($e,'admin');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            $order = Order::with(['user','order_details','order_details.product'])->where(['id' => $id])->first();
            return view('admin.orders.view',compact('order'));
        } catch (\Exception $e) {
            return catchReponse($e,'admin');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
