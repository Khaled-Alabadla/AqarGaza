<?php

namespace App\Http\Controllers\Dashboard;


use App\Models\Attachment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class EmployeeAttachmentsController extends Controller
{
    public function index($id)
    {

        $user = User::with('attachments')->latest()->findOrFail($id);

        if (Gate::allows('users.attachments.index')) {

            $user = User::with('attachments')->findOrFail($id);
        } elseif (Gate::allows('users.user.attachments.index')) {

            if ($id != Auth::id()) {

                abort(403, 'غير مسموح لك بالدخول إلى هذه الصفحة');
            }

            $user = User::with('attachments')->findOrFail($id);
        } else {
            // Unauthorized access
            abort(403, 'غير مسموح لك بالدخول إلى هذه الصفحة');
        }

        return view('dashboard.users.attachments.index', compact('user'));
    }

    public function create($id)
    {
        if (Gate::allows('users.attachments.create') || Gate::allows('users.user.attachments.create')) {

            $user = User::findOrFail($id);
        } else {

            abort(403, 'غير مسموح لك بالدخول إلى هذه الصفحة');
        }

        return view('dashboard.users.attachments.create', compact('user'));
    }

    public function store(Request $request, $id)
    {
        if (
            !Gate::allows('users.attachments.create') &&
            !(Gate::allows('users.user.attachments.create') && $id == Auth::id())
        ) {
            // User is not authorized to create any attachment or personal attachment
            abort(403, 'غير مسموح لك بالدخول إلى هذه الصفحة');
        }

        $request->validate([
            'file' => 'required|file|mimes:jpg,png,pdf,jpeg|max:10240', // Add size limit if needed
        ], [
            'file.required' => 'من فضلك قم بإرفاق ملف',
            'file.mimes' => 'هذا النوع من الملفات غير مسموح، الملفات المسموحة: pdf, jpg, png, jpeg',
            'file.max' => 'حجم الملف كبير جداً، الحد الأقصى هو تقريباً 10 ميحا'
        ]);

        // Find the user
        $user = User::findOrFail($id);

        // Define the directory path within the 'public_uploads' disk
        $directory = "users/{$user->identity_number}/attachments";

        // Get the original file name
        $file_name = $request->file('file')->getClientOriginalName();

        // Check if a file with the same name already exists
        $existing_file = Storage::disk('public_uploads')->exists("{$directory}/{$file_name}");

        if ($existing_file) {
            // Option 1: Reject the upload
            return redirect()->back()
                ->withErrors(['file' => 'يوجد بالفعل ملف بنفس الاسم']);
            // Option 2: Append a unique identifier to the file name
            // $file_name = pathinfo($file_name, PATHINFO_FILENAME)
            //     . '_' . time()
            //     . '.' . $request->file('file')->getClientOriginalExtension();
        }

        // Store the file in the public_uploads disk
        $file_path = $request->file('file')->storeAs($directory, $file_name, 'public_uploads');

        // Create the attachment record in the database
        $user->attachments()->create([
            'file_name' => $file_name,
        ]);

        return redirect()->route('dashboard.user.attachments.index', $id)
            ->with('success', 'تمت إضافة المرفق بنجاح');
    }


    public function open_file($identity_number, $file_name)
    {
        // Build the file path within the 'public_uploads' disk
        $filePath = "users/$identity_number/attachments/$file_name";

        // Check if the file exists
        if (!Storage::disk('public_uploads')->exists($filePath)) {
            return redirect()->back()->with('error', 'File not found.');
        }

        // Get the full file path
        $absolutePath = Storage::disk('public_uploads')->path($filePath);

        // Get the MIME type of the file
        $mimeType = mime_content_type($absolutePath);

        // Return the file to be opened in the browser
        return response()->file($absolutePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . basename($absolutePath) . '"',
        ]);
    }

    public function destroy($id)
    {
        if (
            !Gate::allows('users.attachments.delete') &&
            !(Gate::allows('users.user.attachments.delete') && $id == Auth::id())
        ) {
            // User is not authorized to create any attachment or personal attachment
            abort(403, 'غير مسموح لك بالدخول إلى هذه الصفحة');
        }
        $attachment = Attachment::find($id);

        // dd($attachment);
        if (!$attachment) {
            return redirect()->back()->with('error', 'Attachment not found.');
        }

        // Build the file path based on database information
        $filePath = "users/{$attachment->user->identity_number}/attachments/{$attachment->file_name}";

        // Delete the file if it exists
        if (Storage::disk('public_uploads')->exists($filePath)) {
            Storage::disk('public_uploads')->delete($filePath);
        }

        // Delete the database record
        if ($attachment->delete()) {
            return redirect()->back()->with('success', 'تم حذف المرفق بنجاح');
        }

        return redirect()->back()->with('error', 'خطأ في عملية الحذف');
    }

    public function download($id)
    {
        // Find the attachment record in the database
        $attachment = Attachment::find($id);

        if (!$attachment) {
            return redirect()->back()->with('error', 'Attachment not found.');
        }

        // Build the file path based on the database information
        $filePath = "users/{$attachment->user->identity_number}/attachments/{$attachment->file_name}";

        // Check if the file exists
        if (!Storage::disk('public_uploads')->exists($filePath)) {
            return redirect()->back()->with('error', 'File not found.');
        }

        // Return the file as a download response
        return Storage::disk('public_uploads')->download($filePath, $attachment->file_name);
    }
}
