<?php

namespace App\Http\Controllers;

use App\Models\Post;

class DashboardController extends Controller
{
    public function index()
    {
        $posts = Post::all();
        $posts = Post::paginate(10);
        
        return view('dashboard', compact('posts'));
    }
}

