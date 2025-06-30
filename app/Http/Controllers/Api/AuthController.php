<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validated =  $request->validate([
                'name' => 'required|string|max:255|min:10',
                'email' => 'required|string|lowercase|email|max:255|unique:users,email',
                'password' => ['required', 'confirmed', 'min:8'],
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
                'password.min' => 'كلمة المرور يجب أن تحتوي على 8 أحرف أو أكثر'
            ]);

            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $user->sendEmailVerificationNotification();

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token,
                'message' => 'تم التسجيل بنجاح، يرجى التحقق من بريدك الإلكتروني لتفعيل الحساب.',
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'response_code' => 422,
                'status'        => 'error',
                'message'       => 'خطأ في التحقق من صحة البيانات',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {

            return response()->json([
                'response_code' => 500,
                'status'        => 'error',
                'message'       => 'فشل التسجيل',
            ], 500);
        }
    }

    /**
     * Login and return auth token.
     */
    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => ['required', 'string', 'email'],
                'password' => ['required'],
            ], [
                'email.required' => 'البريد الإلكتروني مطلوب',
                'email.email' => 'البريد الإلكتروني غير صالح',
                'password.required' => 'كلمة المرور مطلوبة',
            ]);

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $token = $user->createToken('auth_token')->plainTextToken;

                return response()->json([
                    'response_code' => 200,
                    'status' => 'success',
                    'user' => $user,
                    'token' => $token,
                ], 200);
            }

            return response()->json([
                'response_code' => 401,
                'status' => 'error',
                'errors' => [
                    'credentials' => ['بيانات تسجيل الدخول غير صحيحة'],
                ],
            ], 401);
        } catch (ValidationException $e) {
            return response()->json([
                'response_code' => 422,
                'status' => 'error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'response_code' => 500,
                'status' => 'error',
                'message' => 'فشل التسجيل',
                'errors' => [$e->getMessage()],
            ], 500);
        }
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'تم تسجيل الخروج بنجاح']);
    }

    public function verifyEmail(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return response()->json(['message' => 'Invalid verification link'], 400);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified'], 200);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return response()->json(['message' => 'Email verified successfully'], 200);
    }

    public function socialRedirect(Request $request, $provider)
    {
        try {
            // Validate the provider from the route parameter
            $validated = validator(['provider' => $provider], [
                'provider' => ['required', 'in:google'],
            ], [
                'provider.required' => 'المزود مطلوب',
                'provider.in' => 'المزود يجب أن يكون google',
            ])->validate();

            $redirectUrl = Socialite::driver($provider)->stateless()->redirect()->getTargetUrl();

            return response()->json([
                'response_code' => 200,
                'status' => 'success',
                'redirect_url' => $redirectUrl,
                'message' => 'تم إنشاء رابط إعادة التوجيه بنجاح',
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'response_code' => 422,
                'status' => 'error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'response_code' => 500,
                'status' => 'error',
                'message' => 'فشل إعادة توجيه تسجيل الدخول الاجتماعي',
                'errors' => [$e->getMessage()],
            ], 500);
        }
    }

    /**
     * Handle social provider callback and authenticate user.
     */
    public function socialCallback(Request $request)
    {
        try {
            // Validate input
            $request->validate([
                'access_token' => 'required_without:code|string',
                'code' => 'required_without:access_token|string',
            ]);

            $socialUser = null;

            // Handle access token or authorization code
            if ($request->has('code')) {
                // PKCE or authorization code flow
                $socialUser = Socialite::driver('google')->stateless()->userFromCode($request->input('code'));
            } elseif ($request->has('access_token')) {
                // Validate access token with Google's tokeninfo endpoint
                $response = Http::get('https://www.googleapis.com/oauth2/v3/tokeninfo', [
                    'access_token' => $request->input('access_token'),
                ]);

                if ($response->failed()) {
                    return response()->json([
                        'response_code' => 401,
                        'status' => 'error',
                        'message' => 'رمز وصول غير صالح',
                        'errors' => ['Invalid access token'],
                    ], 401);
                }

                $socialUser = (object) $response->json();
            } else {
                return response()->json([
                    'response_code' => 400,
                    'status' => 'error',
                    'message' => 'لم يتم تقديم رمز وصول أو رمز تفويض',
                    'errors' => ['No access token or code provided'],
                ], 400);
            }

            // Find or create user
            $user = User::where([
                'provider' => 'google',
                'provider_id' => $socialUser->sub ?? $socialUser->id,
            ])->first();

            if (!$user) {
                $user = User::create([
                    'name' => $socialUser->name,
                    'email' => $socialUser->email,
                    'password' => Hash::make(Str::random(8)),
                    'provider' => 'google',
                    'provider_id' => $socialUser->sub ?? $socialUser->id,
                    'provider_token' => $socialUser->token ?? $request->input('access_token'),
                    'role' => 'user',
                    'email_verified_at' => now(),
                ]);
            } else {
                // Update provider token
                $user->update(['provider_token' => $socialUser->token ?? $request->input('access_token')]);
            }

            // Revoke existing tokens and issue a new one
            $user->tokens()->delete();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'response_code' => 200,
                'status' => 'success',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ],
                'token' => $token,
                'message' => 'تم تسجيل الدخول بنجاح',
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'response_code' => 500,
                'status' => 'error',
                'message' => 'فشل تسجيل الدخول الاجتماعي',
                'errors' => [$e->getMessage()],
            ], 500);
        }
    }
}
