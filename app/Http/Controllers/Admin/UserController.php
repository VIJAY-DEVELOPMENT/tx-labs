<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $data = User::where(['is_admin' => '0'])->orderBy('id','DESC');
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $btn = "<span class='d-flex'>";
                        $btn .= '<a href="' . route('admin.users.edit', ['user' => $row->id]) . '" data-title="User Edit" class="btn  btn-primary btn-sm mx-1 modal-link" title="Edit">Edit</a>';
                        $btn = $btn . '<a href="' . route('admin.users.destroy', ['user' => $row->id]) . '" class="btn btn-danger btn-sm mx-1 delete-users" title="Delete">Delete</a>';
                        $btn .= "<span>";
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }

            $title = "Users";
            return view('admin.users.index', compact('title'));
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
            return view('admin.users.create');
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
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'phone_no' => ['required', 'numeric', 'digits:10', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
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
                User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone_no' => $request->phone_no,
                    'password' => Hash::make($request->password),
                ]);

                return Response::json(array(
                    'error' => false,
                    'errors' => null,
                    'success' => true,
                    'msg' => "User Created successfully"
                ));
            }
        } catch (\Exception $e) {
            return catchReponse($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try{
            $user = User::where(['id' => $id])->first();
            return view('admin.users.create',compact('user'));
        } catch (\Exception $e) {
            return catchReponse($e,'admin');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $rules = [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($id)],
                'phone_no' => ['required', 'numeric', 'digits:10', Rule::unique('users')->ignore($id)],
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
                User::where('id',$id)->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone_no' => $request->phone_no,
                ]);

                return Response::json(array(
                    'error' => false,
                    'errors' => null,
                    'success' => true,
                    'msg' => "User Updated successfully"
                ));
            }
        } catch (\Exception $e) {
            return catchReponse($e,'admin');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            User::where('id',$id)->delete();
            return Response::json(array(
                'error' => false,
                'errors' => null,
                'success' => true,
                'msg' => "User deleted successfully"
            ));
        } catch (\Exception $e) {
            return catchReponse($e,'admin');
        }
    }
}
