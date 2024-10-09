<x-app-layout>
    <div class="container mx-auto mt-8">

        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Create New Post
            </h2>
        </x-slot>

        <form id="postForm" action="{{ route('posts.store') }}" method="POST"
            class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf

            <!-- Title Field -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
                    Title
                </label>
                <input type="text" name="title" id="title" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
            </div>

            <!-- Description Field -->
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                    Description
                </label>
                <textarea name="description" id="description" rows="5" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
            </div>

            <!-- AI Outline Section -->
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="outline">
                    AI Outline
                </label>
                <textarea name="outline" id="outline" rows="5"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                <div id="outline-status" class="text-gray-600 mb-2" style="display: none;"></div>

            </div>

            <!-- Meta Title Field -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="meta_title">
                    Meta Title
                </label>
                <input type="text" name="meta_title" id="meta_title" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
            </div>

            <!-- Meta Description Field -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="meta_description">
                    Meta Description
                </label>
                <textarea name="meta_description" id="meta_description" rows="3" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
            </div>

            <!-- Keywords Field -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="keywords">
                    Keywords
                </label>
                <input type="text" name="keywords" id="keywords" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
            </div>

            <!-- Status Dropdown -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="status">
                    Status
                </label>
                <select name="status" id="status" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="" disabled selected>Select Status</option>
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                    <option value="pending">Pending</option>
                </select>
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
                dateFormat: "Y-m-d H:i", // Adjust format as necessary
                minDate: "today" // Optional: Prevent selection of past dates
            });

            let timer;

            // $('#title, #description').on('keyup', function() {
            //     const data = {
            //         title: $('#title').val(),
            //         description: $('#description').val(),
            //         _token: csrfToken
            //     };

            //     clearTimeout(timer);
            //     timer = setTimeout(function() {
            //         $('#outline').prop('disabled', true);
            //         $('#outline-status').text('Outline is being generated...').show();
            //         $.ajax({
            //             url: '{{ url('/posts/generate-outline') }}',
            //             type: 'POST',
            //             data: data,
            //             success: function(response) {
            //                 if (response.outline) {
            //                     $('#outline').val(response.outline);
            //                 }
            //                 $('#outline').prop('disabled', false);
            //                 $('#outline-status').hide();
            //             },
            //             error: function(error) {
            //                 console.error('Error generating outline:', error);
            //                 $('#outline').prop('disabled', false);
            //                 $('#outline-status').text('Error generating outline. Please try again.').show();
            //             }
            //         });
            //     }, 2000);

            //     $('#outline').on('keyup', function() {
            //         const seoData = {
            //             outline: $('#outline').val(),
            //             _token: csrfToken
            //         };

            //         clearTimeout(timer);
            //         timer = setTimeout(function() {
            //             $.ajax({
            //                 url: '{{ url('/posts/generate-seo') }}',
            //                 type: 'POST',
            //                 data: seoData,
            //                 success: function(response) {
            //                     if (response.meta_title) {
            //                         $('#meta_title').val(response.meta_title);
            //                     }
            //                     if (response.meta_description) {
            //                         $('#meta_description').val(response
            //                             .meta_description);
            //                     }
            //                     if (response.keywords) {
            //                         $('#keywords').val(response.keywords);
            //                     }
            //                 },
            //                 error: function(error) {
            //                     console.error('Error generating SEO data:',
            //                         error);
            //                 }
            //             });
            //         }, 2000); // Adjust the delay as needed
            //     });
            // });

        });
    </script>
</x-app-layout>
