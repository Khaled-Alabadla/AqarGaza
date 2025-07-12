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
        $image_name = rand() . time() . $request->file('image')->getClientOriginalName();

        $image_path = $request->file('image')->storeAs('uploads/blogs', $image_name, 'public_uploads');

        Blog::create([
            'title' => $request->post('title'),
            'content' => $request->post('content'),
            'image' => $image_path,
            'user_id' => Auth::id()
        ]);

        return redirect()->route('dashboard.blogs.index')->with('success', 'تمت الإضافة بنجاح');
    }

    public function edit(Blog $blog)
    {
        Gate::authorize('blogs.update');

        return view('dashboard.blogs.edit', compact('blog'));
    }

    public function update(BlogRequest $request, Blog $blog)
    {
        $image_path = $blog->image;

        if ($request->hasFile('image')) {
            Storage::disk('public_uploads')->delete($blog->image);

            $image_name = rand() . time() . $request->file('image')->getClientOriginalName();

            $image_path = $request->file('image')->storeAs('uploads/blogs', $image_name, 'public_uploads');
        }
        $blog->update([
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => Auth::id(),
            'image' => $image_path
        ]);

        return redirect()->route('dashboard.blogs.index')->with('success', 'تم التعديل بنجاح');
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

        return redirect()->route('dashboard.blogs.index')->with('success', 'تم الحذف بنجاح');
    }
}
