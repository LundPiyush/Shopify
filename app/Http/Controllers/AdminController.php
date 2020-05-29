<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index(){
        echo 'hi';
        exit;
        $menu_active=1;
        return view('backEnd.index',compact('menu_active'));
    }
}
?>