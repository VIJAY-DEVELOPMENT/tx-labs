<?php

use App\Models\Cart;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;

function catchReponse($e,$user_type = NULL)
{
    Log::channel('cacheerror')->info(date('[Y-m-d H:i:s]') . " " . $e->getMessage());
    if (request()->ajax()) 
    {
        return Response::json(
            array(
                'error' => true,
                'errors' => [
                    "error" => $e->getMessage(),
                ],
                'success' => false,
                'data' => [],
                'msg' => "Something went wrong",
            )
        );
    }
    else
    {
        if (isset($user_type) && $user_type == "admin") 
        {
            return redirect()->route('admin.home')->with('danger', "Something went wrong (" . $e->getMessage() . ")");
        }
        return redirect()->route('home')->with('danger', "Something went wrong (" . $e->getMessage() . ")");
    }
}

function getCartCount()
{
    if (Auth::check()) 
    {
        return Cart::where('user_id',Auth::user()->id)->sum('qty');
    }
    else
    {
        $cart = session()->get('cart', []);
        $total = 0;
        foreach ($cart as $key => $item) 
        {
            $total = ((int)$total + (int)$item['qty']);
        }
        return $total;
    }
}

function fileupload($value, $rand = NULL, $dir = NULL)
{
    $imageName = $value->getClientOriginalName();

    $fileNameWithoutExtension = pathinfo($imageName, PATHINFO_FILENAME);
    $slug = Str::slug($fileNameWithoutExtension, '-');
    if (($rand != NULL) && $rand == "Yes") {
        $slug .= date('YmdHis') . '-' . env('APP_ENV');
    }

    $fileExtension = $value->getClientOriginalExtension();
    $imageName = $slug . '.' . $fileExtension;

    if (($dir != NULL)) {
        $directory = $dir . "/" . date('Y') . "/" . date('m');
    } else {
        $directory = "uploads/" . date('Y') . "/" . date('m');
    }

    // Check if a file with the same name already exists in the directory
    if (Storage::disk('public_upload')->exists($directory . '/' . $imageName)) {
        $extension = $value->getClientOriginalExtension();
        $basename = pathinfo($imageName, PATHINFO_FILENAME);
        $counter = 1;

        // Append a dynamic number to the filename until it's unique
        while (Storage::disk('public_upload')->exists($directory . '/' . $imageName)) {
            $imageName = $basename . '_' . $counter . '.' . $extension;
            $counter++;
        }
    }

    Storage::disk('public_upload')->putFileAs($directory, $value, $imageName, 'public');
    $path = $directory . '/' . $imageName;

    return $path;
}