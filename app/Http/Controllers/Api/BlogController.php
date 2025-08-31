<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BlogController extends Controller
{
    public function index()
    {
        try {


            // Fetch other blogs, skipping the main one
            $blogs = Blog::all();

            // Fetch popular posts (based on views)
            $popularPosts = Blog::orderBy('views', 'desc')->take(3)->get();

            return response()->json([
                'status' => true,
                'data' => [
                    'blogs' => $blogs,
                    'popularPosts' => $popularPosts,
                ],
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display a single blog post
     */
    public function show(Blog $blog)
    {
        try {
            // Increment views
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

            return response()->json([
                'status' => true,
                'data' => [
                    'blog' => $blog,
                    'relatedPosts' => $relatedPosts,
                    'previousPost' => $previousPost,
                    'nextPost' => $nextPost,
                    'popularPosts' => $popularPosts,
                ],
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
