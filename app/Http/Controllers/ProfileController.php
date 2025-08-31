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
        $page = Page::where('name', 'profile')->select('name', 'title', 'subtitle')->first();

        $user = Auth::user();

        return view('front.profile', compact('user', 'page'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:10',
            'image' => 'nullable|image|mimes:png,jpg,svg,jpeg',
            'nullable',
            'string',
            'max:20',
            'regex:/^\+?\d{7,15}$/'
        ], [
            'name.required' => 'الاسم مطلوب',
            'name.min' => 'يجب ألا يقل الاسم عن 10 أحرف',
            'image.image' => 'قم بإدخال صورة صالحة',
            'image.mimes' => 'امتداد الصورة غير مسموح، الامتدادات المسموحة: jpg, png, svg, jpeg',
            'phone.regex' => 'رقم الهاتف يجب أن يكون بين 7 و15 رقمًا، ويمكن أن يبدأ بـ +.',
        ]);

        /** @var App/Models/User $user */
        $user = Auth::user();

        $file_path = $user->image;

        if ($request->hasFile('image')) {
            $file = $request->file('image');

            // Define folder inside htdocs/uploads
            $directory = base_path('../uploads/users/profiles');

            // Create the folder if it doesn’t exist
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }

            // Unique file name
            $file_name = rand() . time() . '.' . $file->getClientOriginalExtension();

            // Move file to htdocs/uploads/users/profiles
            $file->move($directory, $file_name);

            // Save relative path (for DB)
            $file_path = "uploads/users/profiles/" . $file_name;

            // Delete old image if exists
            if ($user->image && file_exists(public_path($user->image))) {
                unlink(public_path($user->image));
            }
        }

        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'image' => $file_path,
            'address' => $request->address
        ]);

        flash()->success('تم تعديل الملف الشخصي بنجاح');

        return redirect()->back();
    }
}
