<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        // Fetch the latest main blog
        $mainBlog = Blog::latest()->first();

        // Fetch other blogs, skipping the main one
        $blogs = Blog::latest()->skip(1)->take(12)->get();

        // Fetch popular posts (based on views)
        $popularPosts = Blog::orderBy('views', 'desc')->take(3)->get();

        return view('front.blog.index', compact('blogs', 'mainBlog', 'popularPosts'));
    }


    public function show(Blog $blog)
    {
        // Increment views (if tracking)
        $blog->increment('views');

        // Fetch related posts (recent posts, excluding current post)
        $relatedPosts = Blog::where('id', '!=', $blog->id)
            ->latest()
            ->take(3)
            ->get();

        // Fetch previous and next posts
        $previousPost = Blog::where('id', '<', $blog->id)->orderBy('id', 'desc')->first();
        $nextPost = Blog::where('id', '>', $blog->id)->orderBy('id', 'asc')->first();

        // Fetch popular posts for the sidebar
        $popularPosts = Blog::where('id', '<>', $blog->id)->orderBy('views', 'desc')->take(3)->get();

        return view('front.blog.show', compact('blog', 'relatedPosts', 'previousPost', 'nextPost', 'popularPosts'));
    }
}
