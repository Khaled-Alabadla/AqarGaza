<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Log all files in the request for debugging

            $messages = [
                'name.required' => 'الاسم مطلوب',
                'name.min' => 'يجب ألا يقل الاسم عن 10 أحرف',
                'email.required' => 'البريد الإلكتروني مطلوب',
                'email.email' => 'البريد الإلكتروني غير صالح',
                'image.image' => 'قم بإدخال صورة صالحة',
                'image.mimes' => 'امتداد الصورة غير مسموح، الامتدادات المسموحة: jpg, png, svg, jpeg',
                'phone.regex' => 'رقم الهاتف يجب أن يكون بين 7 و15 رقمًا، ويمكن أن يبدأ بـ +.',

            ];

            $validated = $request->validate([
                'name' => 'required|min:10',
                'email' => 'required|email',
                'phone' => 'required',
                'string',
                'max:20',
                'regex:/^\+?\d{7,15}$/',
                'address' => 'nullable|string|max:255',
                'image' => 'nullable|image|mimes:png,jpg,svg,jpeg', // Added max size (2MB)
            ], $messages);

            /** @var App/Models/User $user */
            $user = Auth::user();
            $file_path = $user->image; // Default to existing image

            if ($request->hasFile('image')) {
                $directory = "users/profiles";
                $file_name = rand() . time() . $request->file('image')->getClientOriginalName();
                $file_path = $request->file('image')->storeAs($directory, $file_name, 'public_uploads');

                if ($user->image) {
                    Storage::disk('public_uploads')->delete($user->image);
                }
            } else {
                $file_path = $user->image ?? null; // Ensure null if no image is uploaded
            }

            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? $user->phone,
                'address' => $validated['address'] ?? $user->address,
                'image' => $file_path,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'تم تعديل الملف الشخصي بنجاح',
                'data' => [
                    'user' => $user->only(['name', 'email', 'phone', 'address', 'image']),
                ],
            ], Response::HTTP_OK);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
