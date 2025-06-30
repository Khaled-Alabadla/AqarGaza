<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Show the registration page.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|min:10',
            'email' => 'required|string|lowercase|email|max:255|unique:users,email',
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'name.required' => 'يرجى إدخال الاسم',
            'name.max' => 'الاسم كبير للغاية',
            'name.min' => 'الاسم يجب أن يحتوي على 10 أحرف على الأقل',

            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.lowercase' => 'البريد الإلكتروني يجب أن يكون بحروف صغيرة',
            'email.email' => 'يرجى إدخال بريد إلكتروني صالح.',
            'email.max' => 'البريد الإلكتروني يجب ألا يزيد عن 255 حرفًا',
            'email.unique' => 'هذا البريد الإلكتروني مسجل بالفعل',

            'password.required' => 'كلمة المرور مطلوبة',
            'password.confirmed' => 'تأكيد كلمة المرور غير مطابق',
        ]);


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Fire email verification
        event(new Registered($user));

        // Login the user
        Auth::login($user);

        return redirect()->route('verification.notice')->with('status', 'Registration successful. Please check your email to verify your account.');
    }
}
