<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class FrontAuthController extends Controller
{
    public function edit_password()
    {
        return view('front.auth.edit_password');
    }

    public function update_password(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8',
            'new_password' => 'required|confirmed|min:8'
        ], [
            'password.required' => 'كلمة المرور مطلوبة',
            'password.min' => 'كلمة المرور يجب ألا تقل عن 8 أحرف',
            'new_password.required' => 'كلمة المرور الجديدة مطلوبة',
            'new_password.confirmed' => 'تأكيد كلمة المرور خاطئ',
            'new_password.min' => 'كلمة المرور الجديدة يجب ألا تقل عن 8 أحرف'
        ]);

        /** @var App/Models/User $user */
        $user = Auth::user();

        if (!Hash::check($request->password, $user->password)) {
            return redirect()->back()->withErrors([
                'password' => 'كلمة المرور القديمة خاطئة'
            ]);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);


        flash('تم تعديل كلمة المرور بنجاح');

        return redirect()->back();
    }
}
