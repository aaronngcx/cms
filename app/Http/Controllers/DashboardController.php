<?php

namespace App\Http\Controllers;

use App\Models\Post;

class DashboardController extends Controller
{
    public function index()
    {
        $posts = Post::where('status', 'published')
            ->where('published_at', '>=', now())
            ->get();

        return view('dashboard', compact('posts'));
    }
}
