<?php

namespace App\Http\Controllers\Dashboard;



use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function login()
    {
        // return view('auth.login');
        return view('auth.login');
    }
    public function validate_login(Request $request)
    {
        $request->validate([
            'identity_number' => 'required',
            'password' => 'required|min:8',
        ]);

        $user = User::where('identity_number', $request->identity_number)->first();

        if (!$user) {
            return back()->withErrors(['identity_number' => 'رقم الهوية غير موجود'])->withInput();
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'كلمة المرور غير صحيحة'])->withInput();
        }

        Auth::login($user);

        return redirect()->route('dashboard', ['home'])->with('success', 'تم تسجيل الدخول بنجاح');
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('login');
    }
}
