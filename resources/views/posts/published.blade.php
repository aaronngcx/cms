<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold mb-6">Published Posts</h2>
    </x-slot>

    <div class="container mx-auto mt-8">
        @if ($posts->isEmpty())
        <div class="text-gray-500">No published posts available.</div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($posts as $post)
                <div class="bg-white shadow-md rounded-lg overflow-hidden hover:shadow-lg transition-shadow duration-200">
                    <div class="p-4">
                        <h3 class="text-lg font-semibold">{{ $post->title }}</h3>
                        <p class="text-gray-600">{{ Str::limit($post->description, 100) }}</p>
                        <div class="mt-2">
                            <span
                                class="inline-block bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full">
                                {{ ucfirst($post->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination Links -->
        <div class="mt-6">
            {{ $posts->links() }}
        </div>
    @endif
    </div>
    
</x-app-layout>
