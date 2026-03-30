<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maklad\Permission\Models\Role;
use Maklad\Permission\Models\Permission;

class DebugController extends Controller
{

    public function index()
    {
       $role = \App\Models\Role::findById(5);
       dd($role->permissions);
    }
}
