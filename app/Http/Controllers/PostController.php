<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::all();
        $posts = Post::paginate(10);

        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'content' => 'required',
            'status' => 'nullable|string|in:draft,pending,published',
            'media' => 'nullable|array',
            'media.*' => 'file|mimes:jpeg,png,jpg,mp4,mov,avi',
            'published_at' => 'nullable|date|after:now',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:300',
            'keywords' => 'nullable|string',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['status'] = $validated['status'] ?? 'draft';
        $validated['published_at'] = $validated['published_at'] ?? null;

        try {
            $post = Post::create($validated);

            if ($request->has('media')) {
                Log::info('Media files found.');

                foreach ($request->file('media') as $file) {
                    Log::info('Processing file:', [
                        'original_name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getClientMimeType(),
                        'size' => $file->getSize(),
                    ]);

                    try {
                        $path = $file->store('media', 'spaces');
                        Log::info('File stored at: ' . $path);

                        $fullUrl = config('filesystems.disks.spaces.url') . '/' . $path;
                        Log::info('Full Path at: ' . $fullUrl);

                        $post->media()->create([
                            'file_path' => $fullUrl,
                            'file_name' => $file->getClientOriginalName(),
                            'file_type' => $file->getClientMimeType(),
                        ]);
                        Log::info('Media record created successfully.');
                    } catch (\Exception $e) {
                        Log::error('Error storing file: ' . $e->getMessage());
                    }
                }
            } else {
                Log::info('No media files found in the request.');
            }

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
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
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

        $post->update($validated);

        if ($request->has('media')) {
            foreach ($request->file('media') as $file) {
                $existingMedia = $post->media()->where('file_name', $file->getClientOriginalName())->first();

                if (!$existingMedia) {
                    try {
                        $path = $file->store('media', 'spaces');
                        $fullUrl = config('filesystems.disks.spaces.url') . '/' . $path;

                        $post->media()->create([
                            'file_path' => $fullUrl,
                            'file_name' => $file->getClientOriginalName(),
                            'file_type' => $file->getClientMimeType(),
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Error storing media file: ' . $e->getMessage());
                    }
                } else {
                    Log::info('Media file already exists, skipping: ' . $file->getClientOriginalName());
                }
            }
        }

        return redirect()->route('posts.index')->with('success', 'Post updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
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
            return response()->json(['outline' => trim($outline)]);
        } else {
            $errorMessage = $response->json()['error']['message'] ?? 'Failed to generate outline.';
            return response()->json(['error' => $errorMessage], 500);
        }
    }

    public function generateMetaTags(Request $request)
    {
        $request->validate([
            'outline' => 'required|string|max:5000',
        ]);

        $outline = $request['outline'];

        $response = Http::withToken(env('OPENAI_API_KEY'))->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4o-mini',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => "Generate SEO metadata for the following blog post outline:\n\nOutline:\n{$outline}\n\nPlease return the results in the following format:\n\nMeta Title:\n[Your Meta Title Here]\n\nMeta Description:\n[Your Meta Description Here]\n\nKeywords:\n[Your Keywords Here]\n\n(Note: Please ensure that each item is under 160 characters.)"
                ]
            ],
            'max_tokens' => 1000
        ]);

        Log::info('OpenAI API Response:', $response->json());

        if ($response->successful()) {
            $seoData = $response->json()['choices'][0]['message']['content'];

            $metaTitle = '';
            $metaDescription = '';
            $keywords = '';

            preg_match('/Meta Title:\s*(.+?)(?=\n\n|$)/s', $seoData, $titleMatches);
            preg_match('/Meta Description:\s*(.+?)(?=\n\n|$)/s', $seoData, $descriptionMatches);
            preg_match('/Keywords:\s*(.+?)(?=\n\n|$)/s', $seoData, $keywordsMatches);

            $metaTitle = isset($titleMatches[1]) ? trim(str_replace('**', '', $titleMatches[1])) : '';
            $metaDescription = isset($descriptionMatches[1]) ? trim(str_replace('**', '', $descriptionMatches[1])) : '';
            $keywords = isset($keywordsMatches[1]) ? trim(str_replace('**', '', $keywordsMatches[1])) : '';

            return response()->json([
                'meta_title' => $metaTitle,
                'meta_description' => $metaDescription,
                'keywords' => $keywords,
            ]);
        } else {
            $errorMessage = $response->json()['error']['message'] ?? 'Failed to generate SEO metadata.';
            Log::error('OpenAI API Error: ' . $errorMessage);
            return response()->json(['error' => $errorMessage], 500);
        }
    }



    public function published()
    {
        $posts = Post::where('status', 'published')->paginate(10);
        return view('posts.published', compact('posts'));
    }
}
