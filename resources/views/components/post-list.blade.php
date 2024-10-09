<div class="mt-8">
    <h3 class="text-lg font-semibold mb-4">Active Posts</h3>

    @if ($posts->isEmpty())
        <div class="text-gray-500">No posts available.</div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($posts as $post)
                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <div class="p-4">
                        <h4 class="font-semibold">{{ $post->title }}</h4>
                        <p class="text-gray-600">{{ Str::limit($post->description, 100) }}</p>
                        <div class="mt-2">
                            <span class="inline-block bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full">
                                {{ ucfirst($post->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
