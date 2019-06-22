<?php

namespace App\Http\Controllers\Admins;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
/*
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
*/

class AdminsController extends Controller
{

    public function index(Request $request) {
		$user = Auth::user();
        return view('admin.dashboard', ['user'=>$user]);
    }
}
