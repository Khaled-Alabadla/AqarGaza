<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Inertia\Inertia;
use Inertia\Response;

class PasswordResetLinkController extends Controller
{
    /**
     * Show the password reset link request page.
     */
    public function create(Request $request)
    {
        // return Inertia::render('auth/ForgotPassword', [
        //     'status' => $request->session()->get('status'),
        // ]);
        return view('auth.forgot_password', [
            'status' => $request->session()->get('status')
        ]);
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صالح',
        ]);

        Password::sendResetLink(
            $request->only('email')
        );

        return back()->with('status', __('تم إرسال الرابط على البريد الإلكتروني المرفق'));
    }
}
