<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrontAuthController extends Controller
{
    public function edit_password()
    {
        return view('front.auth.edit_password');
    }
}
