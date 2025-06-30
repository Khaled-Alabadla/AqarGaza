<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class PasswordResetController extends Controller
{
    public function create(Request $request)
    {
        return response()->json([
            'response_code' => 200,
            'status' => 'success',
            'message' => 'أدخل بريدك الإلكتروني لتلقي رابط إعادة تعيين كلمة المرور',
        ], 200);
    }

    /**
     * Send a password reset link to the given email.
     */
    public function store(Request $request)
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

            $status = Password::sendResetLink(
                $request->only('email')
            );

            if ($status === Password::RESET_LINK_SENT) {
                return response()->json([
                    'response_code' => 200,
                    'status' => 'success',
                    'message' => 'تم إرسال رابط إعادة تعيين كلمة المرور'
                ], 200);
            }

            return response()->json([
                'response_code' => 422,
                'status' => 'error',
                'errors' =>  'لم نجد مستخدمًا بهذا البريد الإلكتروني'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'response_code' => 500,
                'status' => 'error',
                'message' => 'فشل إرسال رابط إعادة تعيين كلمة المرور',
                'errors' => [$e->getMessage()],
            ], 500);
        }
    }
}
