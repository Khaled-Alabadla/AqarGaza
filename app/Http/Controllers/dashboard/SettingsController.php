<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\EditProfileRequest;
use App\Http\Requests\Dashboard\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    public function reset_password()
    {
        return view('dashboard.settings.password_reset');
    }

    public function reset_password_check(ResetPasswordRequest $request)
    {

        $user_password = Auth::user()->password;

        if (
            Hash::check($request->current_password, $user_password)
            && $request->new_password == $request->confirm_password
        ) {

            /** @var App/Model/User */

            $user = Auth::user();

            $user->update([
                'password' => Hash::make($request->new_password),
            ]);

            return redirect()->back()->with('success', 'تم تغيير كلمة المرور بنجاح');
        }
    }

    public function edit_profile(User $user)
    {
        return view('dashboard.settings.edit_profile', compact('user'));
    }

    public function edit_profile_check(EditProfileRequest $request, $id)
    {

        $request->validate([
            'email' => 'nullable|email|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            'phone' => 'required|max:20',
        ], [
            'email.email' => 'البريد الإلكتروني غير صالح',
            'email.regex' => 'البريد الإلكتروني غير صالح',
            'phone.required' => 'رقم الجوال مطلوب',
            'phone.max' => 'رقم الجوال يتجاوز عدد الأرقام المسموح بها',
        ]);

        $user = User::findOrFail($id);

        $user->update([
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return redirect()->back()->with('success', 'تم تعديل الملف الشخصي بنجاح');
    }
}
