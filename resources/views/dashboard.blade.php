<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    <x-alert />

                    <div class="bg-blue-50 p-4 rounded-lg shadow mb-6">
                        <h3 class="font-semibold text-blue-800 text-lg">Welcome back, {{ auth()->user()->name }}!</h3>
                        <p class="text-gray-600">Here’s what you need to know today:</p>
                    </div>

                    <!-- Dashboard Statistics Section -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                        <div class="bg-blue-100 p-4 rounded-lg shadow">
                            <h4 class="font-medium text-blue-800">Total Posts</h4>
                            <p class="text-2xl">{{ $posts->count() }}</p>
                        </div>
                        <div class="bg-green-100 p-4 rounded-lg shadow">
                            <h4 class="font-medium text-green-800">Published Posts</h4>
                            <p class="text-2xl">{{ $posts->where('status', 'published')->count() }}</p>
                        </div>
                        <div class="bg-yellow-100 p-4 rounded-lg shadow">
                            <h4 class="font-medium text-yellow-800">Drafts</h4>
                            <p class="text-2xl">{{ $posts->where('status', 'draft')->count() }}</p>
                        </div>
                    </div>

                    <!-- Include the Posts Index Section -->
                    @include('posts.index', ['posts' => $posts])
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
