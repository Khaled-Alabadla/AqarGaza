<?php

namespace App\Http\Controllers\Dashboard;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    public function reset_password()
    {
        Gate::authorize('users.reset_password');

        return view('dashboard.settings.password_reset');
    }

    public function reset_password_check(Request $request)
    {
        Gate::authorize('users.reset_password');

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8',
            'confirm_password' => 'required|confirmed:new_password',
        ], [
            'current_password.required' => 'كلمة المرور الحالية مطلوبة',
            'new_password.required' => 'كلمة المرور الجديدة مطلوبة',
            'new_password.min' => 'كلمة المرور الجديدة يجي أن تحتوي على 8 حروف أو أرقام أو رموز فأكثر',
            'confirm_password.required' => 'تأكيد كلمة المرور مطلوب',
            'confirm_password.confirmed' => 'تأكيد كلمة المرور خاطئ',
        ]);

        $user_password = Auth::user()->password;

        if (
            Hash::check($request->current_password, $user_password)
            && $request->new_password == $request->confirm_password
        ) {
            Auth::user()->update([
                'password' => Hash::make($request->new_password),
            ]);

            return redirect()->back()->with('success', 'تم تغيير كلمة المرور بنجاح');
        }
    }

    public function reset_password_to_employee()
    {
        $auth_id = Auth::user()->id;

        $users = User::where('id', '<>', $auth_id)->get();

        return view('dashboardsettings.reset-password-to-user', compact('users'));
    }

    public function verify_reset_password_to_employee(Request $request)
    {
        Gate::authorize('dashboard.users.reset_password_to_employee');

        $request->validate([
            'employee_id' => 'required|exists:users,id',
            'new_password' => 'required|min:8',
            'confirm_password' => 'required|confirmed:new_password',
        ], [
            'employee_id' => 'قم باختيار اسم المستخدم',
            'user.exists' => 'هذا المستخدم غير مسجل في النظام',
            'new_password.required' => 'كلمة المرور الجديدة مطلوبة',
            'new_password.min' => 'كلمة المرور الجديدة يجي أن تحتوي على 8 حروف أو أرقام أو رموز فأكثر',
            'confirm_password.required' => 'تأكيد كلمة المرور مطلوب',
            'confirm_password.confirmed' => 'تأكيد كلمة المرور خاطئ',
        ]);

        if ($request->new_password == $request->confirm_password) {
            User::findOrFail($request->employee_id)->update([
                'password' => Hash::make($request->new_password),
            ]);

            return redirect()->back()->with('success', 'تم تغيير كلمة المرور بنجاح');
        }
    }

    public function edit_profile(User $user)
    {

        Gate::authorize('users.update_profile');

        return view('dashboard.settings.edit_profile', compact('user'));
    }

    public function edit_profile_check(Request $request, $id)
    {

        Gate::authorize('users.update_profile');

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
