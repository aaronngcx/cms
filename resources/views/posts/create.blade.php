<x-app-layout>
    <div class="container mx-auto mt-8">

        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Create New Post
            </h2>
        </x-slot>

        <form id="postForm" action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data"
            class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf

            <!-- Title Field -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
                    Title
                </label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
                @error('title')
                    <span class="text-red-500 text-xs italic">{{ $message }}</span>
                @enderror
            </div>

            <!-- Description Field -->
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                    Description
                </label>
                <textarea name="description" id="description" rows="5" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('description') }}</textarea>
                @error('description')
                    <span class="text-red-500 text-xs italic">{{ $message }}</span>
                @enderror
            </div>

            <!-- Content Section -->
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="content">
                    Content
                </label>
                <textarea name="content" id="content" rows="5"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('content') }}</textarea>
                @error('content')
                    <span class="text-red-500 text-xs italic">{{ $message }}</span>
                @enderror
                <button id="generate-outline-button" type="button"
                    class="bg-green-500 text-white font-semibold py-2 px-4 rounded hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50">
                    Generate Outline
                </button>
                <div id="content-status" class="text-gray-600 mb-2 italic" style="display: none;"></div>
            </div>

            <!-- Meta Title Field -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="meta_title">
                    Meta Title
                </label>
                <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title') }}" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
                @error('meta_title')
                    <span class="text-red-500 text-xs italic">{{ $message }}</span>
                @enderror
            </div>

            <!-- Meta Description Field -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="meta_description">
                    Meta Description
                </label>
                <textarea name="meta_description" id="meta_description" rows="3" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('meta_description') }}</textarea>
                @error('meta_description')
                    <span class="text-red-500 text-xs italic">{{ $message }}</span>
                @enderror
            </div>

            <!-- Keywords Field -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="keywords">
                    Keywords
                </label>
                <input type="text" name="keywords" id="keywords" value="{{ old('keywords') }}" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
                @error('keywords')
                    <span class="text-red-500 text-xs italic">{{ $message }}</span>
                @enderror

                <button id="generate-meta-tags" type="button"
                    class="mt-2 bg-green-500 text-white font-semibold py-2 px-4 rounded hover:bg-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-opacity-50">
                    Generate Meta Tags
                </button>

                <div id="metatags-status" class="text-gray-600 mb-2 italic" style="display: none;"></div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="media">
                    Upload File
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
                    <option value="" disabled {{ old('status') ? '' : 'selected' }}>Select Status</option>
                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                </select>
                @error('status')
                    <span class="text-red-500 text-xs italic">{{ $message }}</span>
                @enderror
            </div>


            <!-- Published At DateTime Picker -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="published_at">
                    Published At
                </label>
                <input type="text" name="published_at" id="published_at"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    placeholder="Select date and time" />
            </div>

            <!-- Submit and Cancel Buttons -->
            <div class="flex items-center justify-between">
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Create Post
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

            let timer;

            $('#generate-outline-button').on('click', function() {
                const title = $('#title').val().trim();
                const description = $('#description').val().trim();

                if (title && description) {
                    clearTimeout(timer);
                    $('#content').prop('disabled', true);
                    $('#content-status').text('Outline is being generated...').show();

                    const data = {
                        title: title,
                        description: description,
                        _token: csrfToken
                    };

                    $.ajax({
                        url: '{{ url('/posts/generate-outline') }}',
                        type: 'POST',
                        data: data,
                        success: function(response) {
                            if (response.outline) {
                                $('#content').val(response.outline);
                            }
                            $('#content').prop('disabled', false);
                            $('#content-status').hide();
                        },
                        error: function(error) {
                            console.error('Error generating outline:', error);
                            $('#content').prop('disabled', false);
                            $('#content-status').text(
                                'Error generating outline. Please try again.').show();
                        }
                    });
                } else {
                    $('#content-status').text('Please fill in both title and description.').show();
                }
            });

            $('#generate-meta-tags').on('click', function() {
                const seoData = {
                    outline: $('#content').val(),
                    _token: csrfToken
                };

                clearTimeout(timer);
                timer = setTimeout(function() {
                    $.ajax({
                        url: '{{ url('/posts/generate-meta-tags') }}',
                        type: 'POST',
                        data: seoData,
                        success: function(response) {
                            console.log(response.meta_title);
                            console.log(response.meta_description);
                            console.log(response.keywords);

                            if (response.meta_title) {
                                $('#meta_title').val(response.meta_title);
                            }
                            if (response.meta_description) {
                                $('#meta_description').val(response
                                    .meta_description);
                            }
                            if (response.keywords) {
                                $('#keywords').val(response.keywords);
                            }
                        },
                        error: function(error) {
                            console.error('Error generating SEO data:',
                                error);
                        }
                    });
                }, 0);
            });

        });
    </script>
</x-app-layout>
