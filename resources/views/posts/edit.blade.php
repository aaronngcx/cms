<x-app-layout>
    <div class="container mx-auto mt-8">

        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Post
            </h2>
        </x-slot>

        <form id="postForm" action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data"
            class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            @method('PUT') <!-- Method spoofing for PUT -->

            <!-- Title Field -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
                    Title
                </label>
                <input type="text" name="title" id="title" value="{{ old('title', $post->title) }}" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
                @error('title')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description Field -->
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                    Description
                </label>
                <textarea name="description" id="description" rows="5" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('description', $post->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <!-- AI Outline Section -->
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="outline">
                    Content
                </label>
                <textarea name="outline" id="outline" rows="5"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('outline', $post->content) }}</textarea>
                <div id="outline-status" class="text-gray-600 mb-2" style="display: none;"></div>
            </div>

            <!-- Meta Title Field -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="meta_title">
                    Meta Title
                </label>
                <input type="text" name="meta_title" id="meta_title"
                    value="{{ old('meta_title', $post->meta_title) }}" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
                @error('meta_title')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <!-- Meta Description Field -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="meta_description">
                    Meta Description
                </label>
                <textarea name="meta_description" id="meta_description" rows="3" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('meta_description', $post->meta_description) }}</textarea>
                @error('meta_description')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <!-- Keywords Field -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="keywords">
                    Keywords
                </label>
                <input type="text" name="keywords" id="keywords" value="{{ old('keywords', $post->keywords) }}"
                    required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
                @error('keywords')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="media">
                    Existing Media
                </label>
                <div class="flex flex-wrap">
                    @foreach ($post->media as $media)
                        <div id="media-{{ $media->id }}" class="relative mr-4 mb-4">
                            <img src="{{ $media->file_path }}" alt="{{ $media->file_name }}"
                                class="h-32 w-32 object-cover rounded" />
                            <button type="button"
                                class="absolute top-0 right-0 bg-red-500 text-white text-xs px-1 rounded"
                                onclick="removeMedia({{ $media->id }})">Remove</button>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Upload New Media Section -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="media">
                    Upload New Media
                </label>
                <input type="file" name="media[]" id="media" multiple
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
            </div>

            <!-- Status Dropdown -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="status">
                    Status
                </label>
                <select name="status" id="status" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="draft" {{ $post->status === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ $post->status === 'published' ? 'selected' : '' }}>Published</option>
                    <option value="pending" {{ $post->status === 'pending' ? 'selected' : '' }}>Pending</option>
                </select>
            </div>

            <!-- Published At DateTime Picker -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="published_at">
                    Published At
                </label>
                <input type="text" name="published_at" id="published_at"
                    value="{{ old('published_at', $post->published_at) }}"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    placeholder="Select date and time" />
            </div>

            <!-- Submit and Cancel Buttons -->
            <div class="flex items-center justify-between">
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Update Post
                </button>
                <a href="{{ route('posts.index') }}"
                    class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <!-- Include Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <!-- Include Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        $(document).ready(function() {
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            flatpickr("#published_at", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                minDate: "today"
            });
        });

        function removeMedia(mediaId) {
            const confirmation = confirm("Are you sure you want to remove this media?");
            if (!confirmation) return;

            $.ajax({
                url: '{{ url('/media/') }}/' + mediaId,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    console.log(response.message);
                    $(`#media-${mediaId}`).remove();
                },
                error: function(xhr) {
                    console.error("Error removing media:", xhr.responseText);
                    alert("Failed to remove media. Please try again.");
                }
            });
        }
    </script>
</x-app-layout>
