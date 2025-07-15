<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $page = Page::where('name', 'contact')->select('name', 'title', 'subtitle')->first();

        $user = Auth::user();

        return view('front.profile', compact('user', 'page'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:10',
            'email' => 'required|email',
            'image' => 'nullable|image|mimes:png,jpg,svg,jpeg'
        ], [
            'name.required' => 'الاسم مطلوب',
            'name.min' => 'يجب ألا يقل الاسم عن 10 أحرف',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صالح',
            'image.image' => 'قم بإدخال صورة صالحة',
            'image.mimes' => 'امتداد الصورة غير مسموح، الامتدادات المسموحة: jpg, png, svg, jpeg'
        ]);

        /** @var App/Models/User $user */
        $user = Auth::user();

        $file_path = $user->image;

        if ($request->hasFile('image')) {
            // Define the directory path within the 'public_uploads' disk
            $directory = "uploads/users/profiles";
            // Get the original file name
            $file_name = rand() . time() . $request->file('image')->getClientOriginalName();

            // Store the file in the public_uploads disk
            $file_path = $request->file('image')->storeAs($directory, $file_name, 'public_uploads');

            if ($user->image) {
                Storage::disk('public_uploads')->delete($user->image);
            }
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'image' => $file_path,
            'address' => $request->address
        ]);

        flash()->success('تم تعديل الملف الشخصي بنجاح');

        return redirect()->back();
    }
}
