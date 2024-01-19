<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rule;
use App\Models\Product;
use Illuminate\Http\Request;
use DataTables;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $data = Product::orderBy('id','DESC');
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $btn = "<span class='d-flex'>";
                        $btn .= '<a href="' . route('admin.products.edit', ['product' => $row->id]) . '" data-title="Product Edit" class="btn  btn-primary btn-sm mx-1 modal-link" title="Edit">Edit</a>';
                        $btn = $btn . '<a href="' . route('admin.products.destroy', ['product' => $row->id]) . '" class="btn btn-danger btn-sm mx-1 delete-products" title="Delete">Delete</a>';
                        $btn .= "<span>";
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }

            $title = "Products";
            return view('admin.products.index', compact('title'));
        } catch (\Exception $e) {
            return catchReponse($e,'admin');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try{
            return view('admin.products.create');
        } catch (\Exception $e) {
            return catchReponse($e,'admin');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            
            $rules = [
                'product_name' => ['required', 'string', 'max:255'],
                'slug' => ['required', 'string', 'max:255', 'unique:products'],
                'sku' => ['required', 'string', 'max:255', 'unique:products'],
                'price' => ['required', 'numeric'],
                'product_image' => ['required','file','mimes:jpeg,jpg,png,webp','max:2048'],
            ];

            $validator = Validator::make($request->all(), $rules, [
                'required' => 'Required',
            ]);

            if ($validator->fails()) {
                return Response::json(array(
                    'error' => true,
                    'errors' => $validator->getMessageBag(),
                    'success' => false,
                    'msg' => "",
                ));
            } else {
                $data = [];
                if ($request->hasFile('product_image')) 
                {
                    $data['product_image'] = fileupload($request->product_image);
                }

                $data['product_name'] = $request->product_name;
                $data['slug'] = $request->slug;
                $data['sku'] = $request->sku;
                $data['price'] = $request->price;

                Product::create($data);

                return Response::json(array(
                    'error' => false,
                    'errors' => null,
                    'success' => true,
                    'msg' => "Product Created successfully"
                ));
            }
        } catch (\Exception $e) {
            return catchReponse($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        try{
            return view('admin.products.create',compact('product'));
        } catch (\Exception $e) {
            return catchReponse($e,'admin');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        try {
            $rules = [
                'product_name' => ['required', 'string', 'max:255'],
                'slug' => ['required', 'string', 'max:255', Rule::unique('products')->ignore($product->id)],
                'sku' => ['required', 'string', 'max:255', Rule::unique('products')->ignore($product->id)],
                'price' => ['required', 'numeric'],
                'product_image' => ['nullable','file','mimes:jpeg,jpg,png,webp','max:2048'],
            ];

            $validator = Validator::make($request->all(), $rules, [
                'required' => 'Required',
            ]);

            if ($validator->fails()) {
                return Response::json(array(
                    'error' => true,
                    'errors' => $validator->getMessageBag(),
                    'success' => false,
                    'msg' => "",
                ));
            } else {
                $data = [];
                if ($request->hasFile('product_image')) 
                {
                    $data['product_image'] = fileupload($request->product_image);
                }

                $data['product_name'] = $request->product_name;
                $data['slug'] = $request->slug;
                $data['sku'] = $request->sku;
                $data['price'] = $request->price;

                $product->update($data);

                return Response::json(array(
                    'error' => false,
                    'errors' => null,
                    'success' => true,
                    'msg' => "Product Updated successfully"
                ));
            }
        } catch (\Exception $e) {
            return catchReponse($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            $product->delete();
            return Response::json(array(
                'error' => false,
                'errors' => null,
                'success' => true,
                'msg' => "Product deleted successfully"
            ));
        } catch (\Exception $e) {
            return catchReponse($e,'admin');
        }
    }
}
