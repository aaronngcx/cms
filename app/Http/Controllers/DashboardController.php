<?php

namespace App\Http\Controllers;

use App\Models\Post;

class DashboardController extends Controller
{
    public function index()
    {
        $posts = Post::where('status', 'published')
            ->where('published_at', '>=', now())
            ->paginate(10);

        return view('dashboard', compact('posts'));
    }
}
