<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\BlogRequest;
use App\Models\Blog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class BlogsController extends Controller
{
    public function index()
    {
        Gate::authorize('blogs.index');

        $blogs = Blog::with('user')->latest()->get();

        return view('dashboard.blogs.index', compact('blogs'));
    }

    public function create()
    {
        Gate::authorize('blogs.create');

        return view('dashboard.blogs.create');
    }

    public function store(BlogRequest $request)
    {
        $file_path = null;

        if ($request->hasFile('image')) {
            $file = $request->file('image');

            // Define folder inside htdocs/uploads
            $directory = base_path('../uploads/blogs');

            // Create the folder if it doesn’t exist
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }

            // Unique file name
            $file_name = rand() . time() . $request->file('image')->getClientOriginalName();

            // Move file to htdocs/uploads/users/profiles
            $file->move($directory, $file_name);

            // Save relative path (for DB)
            $file_path = "uploads/blogs/" . $file_name;
        }

        Blog::create([
            'title' => $request->post('title'),
            'excerpt' => $request->post('excerpt'),
            'content' => $request->post('content'),
            'image' => $file_path,
            'user_id' => Auth::id(),
            'date' => now()->toDateString()
        ]);

        flash()->success('تمت الإضافة بنجاح');

        return redirect()->route('dashboard.blogs.index');
    }

    public function edit(Blog $blog)
    {
        Gate::authorize('blogs.update');

        return view('dashboard.blogs.edit', compact('blog'));
    }

    public function update(BlogRequest $request, Blog $blog)
    {
        $relativePath = $blog->image;

        $directory = base_path('../uploads/blogs');

        if ($request->hasFile('image')) {
            if ($blog->image) {
                $oldPath = base_path('../' . $blog->image);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            $file_name = rand() . time() . '_' . $request->file('main-property-image')->getClientOriginalName();

            $request->file('image')->move($directory, $file_name);

            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }

            // Move file to uploads folder
            $request->file('image')->move($directory, $file_name);

            // Save relative path for DB
            $relativePath = 'uploads/blogs/' . $file_name;
        }

        $blog->update([
            'title' => $request->title,
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'user_id' => Auth::id(),
            'image' => $relativePath
        ]);

        flash()->success('تم التعديل بنجاح');


        return redirect()->route('dashboard.blogs.index');
    }

    public function show(string $id)
    {
        Gate::authorize('blogs.show');

        $blog = Blog::with('user')->latest()->findOrFail($id);

        return view('dashboard.blogs.show', compact('blog'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog)
    {
        Gate::authorize('blogs.delete');

        $blog->delete();

        Storage::disk('public_uploads')->delete($blog->image);

        flash()->success('تم الحذف بنجاح');

        return redirect()->route('dashboard.blogs.index');
    }
}
