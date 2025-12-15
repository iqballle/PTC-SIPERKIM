<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DeveloperRthController extends Controller
{
    public function index()
    {
        return view('developer.rth.index');
    }
}