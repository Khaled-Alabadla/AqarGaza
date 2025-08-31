<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'message' => 'required|string',
                'phone' => 'required',
                'string',
                'max:20',
                'regex:/^\+?\d{7,15}$/',
            ], [
                'name.required' => 'الاسم مطلوب',
                'email.required' => 'البريد الإلكتروني مطلوب',
                'email.email' => 'البريد الإلكتروني غير صالح',
                'message.required' => 'الرجاء كتابة الرسالة',
                'phone.required' => 'رقم الجوال مطلوب',
                'phone.regex' => 'رقم الهاتف يجب أن يكون بين 7 و15 رقمًا، ويمكن أن يبدأ بـ +.',
            ]);

            $contact = Contact::create($validated);

            return response()->json([
                'status' => true,
                'message' => 'تم إرسال الرسالة',
                'data' => $contact,
            ], Response::HTTP_CREATED);
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
