<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Post Management') }}
        </h2>
    </x-slot>

    <div class="container mx-auto mt-8">
        <div class="flex justify-between mb-4">
            <h2 class="text-2xl font-bold">Post List</h2>
            <a href="{{ route('posts.create') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Create New Post
            </a>
        </div>

        {{-- Display success or error messages --}}
        @if (session('success'))
            <div class="bg-green-500 text-white p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-500 text-white p-4 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Title
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Description
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($posts as $post)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $post->title }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ Str::limit($post->description, 50) }} {{-- Display a limited description --}}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{-- <span class="inline-block bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full">
                                    {{ ucfirst($post->status) }}
                                </span> --}}

                                <span class="
                                    inline-block text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full
                                    @if ($post->status == 'published') bg-green-100 text-green-800
                                    @elseif ($post->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif ($post->status == 'draft') bg-gray-100 text-gray-800
                                    @endif
                                ">
                                    {{ ucfirst($post->status) }}
                                </span>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @if (Auth::user()->canEdit())
                                    <button class="text-red-600 hover:text-red-900 delete-post" data-id="{{ $post->id }}" data-title="{{ $post->title }}">
                                        Delete
                                    </button>
                                    <a href="{{ route('posts.edit', $post->id) }}" class="text-blue-600 hover:text-blue-900 ml-2">Edit</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $posts->links() }}
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 flex items-center justify-center z-50 hidden bg-gray-800 bg-opacity-50">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full">
            <h3 class="text-lg font-semibold">Confirm Deletion</h3>
            <p class="mt-2">Are you sure you want to delete the post "<span id="post-title"></span>"?</p>
            <div class="mt-4 flex justify-end">
                <button id="confirmDelete" class="bg-red-600 text-white px-4 py-2 rounded-lg">Delete</button>
                <button id="cancelDelete" class="ml-2 px-4 py-2 rounded-lg border border-gray-400">Cancel</button>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            let deleteFormAction = '';

            $('.delete-post').on('click', function () {
                const postId = $(this).data('id');
                const title = $(this).data('title');

                // Set the post title in the modal
                $('#post-title').text(title);

                // Show the modal
                $('#deleteModal').removeClass('hidden');

                // Set the action for the delete confirmation
                deleteFormAction = `{{ url('posts') }}/${postId}`;
                console.log(deleteFormAction);
            });

            $('#confirmDelete').on('click', function () {
                // Create a form to submit the delete request
                const form = $('<form>', {
                    method: 'POST',
                    action: deleteFormAction
                });

                // Add CSRF token and method spoofing for DELETE
                form.append(`@csrf`);
                form.append(`@method('DELETE')`);

                // Append the form to the body and submit it
                $('body').append(form);
                form.submit();
            });

            $('#cancelDelete').on('click', function () {
                // Hide the modal
                $('#deleteModal').addClass('hidden');
            });
        });
    </script>
</x-app-layout>
