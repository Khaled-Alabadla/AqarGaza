<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    public function verify(EmailVerificationRequest $request)
    {
        try {
            /** @var App/Models/User */
            $user = $request->user();

            if ($user->hasVerifiedEmail()) {
                // Issue a new token even if already verified, for consistency
                $user->tokens()->delete();
                $token = $user->createToken('auth_token')->plainTextToken;

                return response()->json([
                    'response_code' => 200,
                    'status' => 'success',
                    'message' => 'البريد الإلكتروني تم التحقق منه بالفعل',
                    'user' => [
                        'id' => $user->id,
                        'email' => $user->email,
                        'role' => $user->role,
                    ],
                    'token' => $token,
                ], 200);
            }

            if ($user->markEmailAsVerified()) {
                /** @var \Illuminate\Contracts\Auth\MustVerifyEmail $user */
                event(new Verified($user));

                // Revoke existing tokens and issue a new one
                $user->tokens()->delete();
                $token = $user->createToken('auth_token')->plainTextToken;

                return response()->json([
                    'response_code' => 200,
                    'status' => 'success',
                    'message' => 'تم التحقق من البريد الإلكتروني بنجاح',
                    'user' => [
                        'id' => $user->id,
                        'email' => $user->email,
                        'role' => $user->role,
                    ],
                    'token' => $token,
                ], 200);
            }

            return response()->json([
                'response_code' => 400,
                'status' => 'error',
                'message' => 'فشل التحقق من البريد الإلكتروني',
                'errors' => ['غير قادر على التحقق من البريد الإلكتروني'],
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'response_code' => 500,
                'status' => 'error',
                'message' => 'خطأ في الخادم',
                'errors' => [$e->getMessage()],
            ], 500);
        }
    }
}
