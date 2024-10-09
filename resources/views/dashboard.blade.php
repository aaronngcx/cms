@extends('layouts.app')

<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Dashboard') }}
    </h2>
</x-slot>

@section('content')
    <div class="py-12 bg-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    @if (session('success'))
                        <div class="bg-green-500 text-white p-4 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif
                    <h3 class="text-lg font-semibold mb-4">{{ __("You're logged in!") }}</h3>

                    {{-- Dashboard Statistics Section --}}
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

                    {{-- Posts Index Section --}}
                    @include('posts.index', ['posts' => $posts])
                </div>
            </div>
        </div>
    </div>
@endsection
