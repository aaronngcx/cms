<div class="post-index">
    <h2 class="text-2xl font-bold mb-6 mt-3">Posts</h2>

    <div class="mb-4">
        <a href="{{ route('posts.create') }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-blue-700 transition duration-200 ease-in-out">Create New Post</a>
    </div>

    @if ($posts->isEmpty())
        <div class="text-gray-500">No posts available.</div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($posts as $post)
                <div class="bg-gray-100 shadow-md rounded-lg overflow-hidden">
                    <div class="p-4">
                        <h3 class="text-lg font-semibold">{{ $post->title }}</h3>
                        <p class="text-gray-600">{{ Str::limit($post->description, 100) }}</p>
                        <div class="mt-2">
                            <span class="inline-block bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full">
                                {{ ucfirst($post->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="flex justify-between p-4 border-t">
                        <button class="text-red-600 hover:text-red-900 font-medium delete-post" data-id="{{ $post->id }}" data-title="{{ $post->title }}">Delete</button>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination Links --}}
        <div class="mt-6">
            {{ $posts->links() }}
        </div>
    @endif
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
