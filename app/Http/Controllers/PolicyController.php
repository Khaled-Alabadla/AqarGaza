<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PolicyController extends Controller
{
    public function responsibility()
    {
        return view('front.responsibility');
    }
    public function privacy()
    {
        return view('front.privacy');
    }
    public function conditions()
    {
        return view('front.conditions');
    }
}
