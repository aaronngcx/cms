<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::all();
        $posts = Post::paginate(10);

        return view('dashboard', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.create'); // This points to the create.blade.php view
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'outline' => 'required',
            'status' => 'nullable|string|in:draft,pending,published',
            // 'media' => 'nullable|array',
            // 'media.*' => 'file|mimes:jpeg,png,jpg,mp4,mov,avi',
            'published_at' => 'nullable|date|after:now',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:160',
            'keywords' => 'nullable|string',
        ]);

        // Assign user and created_by fields
        $validated['created_by'] = Auth::id(); // Save the creator
        $validated['status'] = $validated['status'] ?? 'draft';
        $validated['published_at'] = $validated['published_at'] ?? null;

        try {
            $post = Post::create($validated);
            $post->save();

            return redirect()->route('posts.index')->with('success', 'Post created successfully');
        } catch (\Exception $e) {
            Log::error('Post creation failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => 'Post creation failed, please try again.']);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return response()->json($post->load('creator'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        return response()->json(['message' => 'Display edit form', 'post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post); // Authorization check

        // Validate the request
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'media_assets' => 'nullable|array',
            'media_assets.*' => 'file|mimes:jpeg,png,jpg,mp4,mov,avi',
            'scheduled_for' => 'nullable|date|after:now',
            'status' => 'in:draft,published,scheduled',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:160',
            'keywords' => 'nullable|string'
        ]);

        // Update the post
        $post->update($validated);

        return response()->json(['message' => 'Post updated successfully', 'post' => $post], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        // $this->authorize('delete', $post); // Uncomment if you are using authorization

        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post deleted successfully');
    }


    protected function handleMediaUpload(array $media)
    {
        $mediaPaths = [];

        foreach ($media as $file) {
            $path = $file->store('media', 'public');
            $mediaPaths[] = $path;
        }

        return $mediaPaths;
    }

    public function generateOutline(Request $request)
    {
        $request['title'] = 'How to Start a Vegetable Garden in Your Backyard';
        $request['description'] = 'Learn the essentials of starting your own vegetable garden from scratch. This step-by-step guide covers everything from selecting the right location to choosing the best plants for beginners, ensuring your gardening success.';

        // Make sure to set the API key for OpenAI in your .env file
        $apiKey = env('OPENAI_API_KEY');

        $response = Http::withToken(env('OPENAI_API_KEY'))->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4o-mini',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => "Create a short outline for a blog post based on the following title and description:\n\nTitle: {$request['title']}\n\nDescription: {$request['description']}"
                ]
            ],
            'max_tokens' => 10000
        ]);

        Log::info($response);

        if ($response->successful()) {
            $outline = $response->json()['choices'][0]['message']['content'];
            return response()->json(['outline' => trim($outline)]);  // Return as JSON response
        } else {
            // Handle the error case
            $errorMessage = $response->json()['error']['message'] ?? 'Failed to generate outline.';
            return response()->json(['error' => $errorMessage], 500);
        }
    }

    public function generateSEO(Request $request)
    {
    }
}
