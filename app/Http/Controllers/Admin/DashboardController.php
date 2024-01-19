<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $title = "Dashboard";
            return view('admin.home',compact('title'));
        } catch (\Exception $e) {
            return catchReponse($e,'admin');
        }
    }
}
