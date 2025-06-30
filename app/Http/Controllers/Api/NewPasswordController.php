<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class NewPasswordController extends Controller
{
    public function create(Request $request, $token)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => ['required', 'email'],
            ], [
                'email.required' => 'البريد الإلكتروني مطلوب',
                'email.email' => 'يجب أن يكون البريد الإلكتروني صالحًا',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'response_code' => 422,
                    'status' => 'error',
                    'errors' => $validator->errors(),
                ], 422);
            }

            return response()->json([
                'response_code' => 200,
                'status' => 'success',
                'token' => $token,
                'email' => $request->email,
                'message' => 'أدخل كلمة المرور الجديدة لإعادة تعيين كلمة المرور',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'response_code' => 500,
                'status' => 'error',
                'message' => 'فشل عرض طلب إعادة تعيين كلمة المرور',
                'errors' => [$e->getMessage()],
            ], 500);
        }
    }

    /**
     * Handle the password reset submission.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'token' => ['required'],
                'email' => ['required', 'email'],
                'password' => ['required', 'min:8', 'confirmed'],
            ], [
                'token.required' => 'الرمز مطلوب',
                'email.required' => 'البريد الإلكتروني مطلوب',
                'email.email' => 'يجب أن يكون البريد الإلكتروني صالحًا',
                'password.required' => 'كلمة المرور مطلوبة',
                'password.min' => 'يجب أن تكون كلمة المرور 8 أحرف على الأقل',
                'password.confirmed' => 'تأكيد كلمة المرور غير متطابق',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'response_code' => 422,
                    'status' => 'error',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    $user->forceFill([
                        'password' => Hash::make($password),
                        'remember_token' => Str::random(60),
                    ])->save();

                    event(new PasswordReset($user));
                }
            );

            if ($status === Password::PASSWORD_RESET) {
                return response()->json([
                    'response_code' => 200,
                    'status' => 'success',
                    'message' => 'تم إعادة تعيين كلمة المرور بنجاح'
                ], 200);
            }

            return response()->json([
                'response_code' => 422,
                'status' => 'error',
                'errors' => ['email' => 'الرمز غير صالح'],
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'response_code' => 500,
                'status' => 'error',
                'message' => 'فشل إعادة تعيين كلمة المرور',
                'errors' => [$e->getMessage()],
            ], 500);
        }
    }
}
