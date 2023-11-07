@extends('layouts.app') {{-- Use your layout file if you have one --}}
@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-4">Edit Cookie</h1>

    @if(session('error'))
    <div class="bg-red-100 text-red-500 border border-red-400 px-4 py-2 rounded mb-4">
        {{ session('error') }}
    </div>
    @endif

    <form action="{{ route('cookie.update', $cookie->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
            <input type="text" name="title" id="title" value="{{ $cookie->title }}" class="w-full px-4 py-2 rounded border focus:outline-none focus:ring focus:ring-indigo-500">
            @error('title')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea name="description" id="description" rows="4" class="w-full px-4 py-2 rounded border focus:outline-none focus:ring focus:ring-indigo-500">{{ $cookie->description }}</textarea>
            @error('description')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
            <input type="file" name="image" id="image" class="w-full px-4 py-2 rounded border focus:outline-none focus:ring focus:ring-indigo-500">
            @error('image')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Update Cookie</button>
        </div>
    </form>
</div>
@endsection
