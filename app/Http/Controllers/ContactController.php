<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Page;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $page = Page::where('name', 'contact')->select('name', 'title', 'subtitle')->first();

        return view('front.contact', compact('page'));
    }

    public function contact_save(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required',
            'phone' => 'required'
        ], [
            'name.required' => 'الاسم مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صالح',
            'message.required' => 'الرجاء كتابة الرسالة',
            'phone.required' => 'رقم الجوال مطلوب'
        ]);

        Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message,
            'phone' => $request->phone
        ]);

        flash()->success('تم إرسال الرسالة');

        return redirect()->back();
    }
}
