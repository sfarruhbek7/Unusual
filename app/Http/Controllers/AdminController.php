<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(){
        return view('AdminPanel.index');
    }
    public function diagnose(){
        return view('AdminPanel.diagnose');
    }
}
