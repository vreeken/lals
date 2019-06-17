<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

use App\Page;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminController extends Controller
{

    public function index(Request $request) {

        return view('admin.index');
    }
}
