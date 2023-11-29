@extends('layouts.app')

@section('content')
    <div class="container mx-auto">
        @if(session('success'))
            <div class="bg-green-200 p-4 rounded-md mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-200 p-4 rounded-sm mb-4">
                {{ session('error') }}
            </div>
        @endif
        <p>
            Best Cookie: <b>{{ $best_cookie }}</b>
        </p>
        <h3>Cookies List</h3>
        <ul role="list" class="divide-y divide-gray-100">
            @foreach($cookies as $cookie)
                <li class="flex justify-between gap-x-6 py-5">
                    <div class="flex min-w-0 gap-x-4">
                    <img class="h-12 w-12 flex-none rounded-full bg-gray-50 object-cover" src="{{ asset('storage/' . $cookie->image) }}" alt="">
                    <div class="min-w-0 flex-auto">
                        <p class="text-sm font-semibold leading-6 text-gray-900">{{ $cookie->title }}</p>
                        <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ $cookie->description }}</p>
                    </div>
                    </div>
                    <div class="hidden shrink-0 sm:flex sm:flex-col sm:items-end">
                      <p class="text-sm leading-6 text-gray-900">By Joe</p>
                      <p class="mt-1 text-xs leading-5 text-gray-500">Date <time datetime="2023-01-23T13:23Z">1-1-2023</time></p>
                      <a href="{{ route('cookie.edit', $cookie->id) }}" class="text-indigo-600 hover:text-indigo-800 cursor-pointer">Edit
                      </a>
                      <form action="{{ route('cookie.destroy', $cookie->id) }}" method="POST">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                      </form>
                    </div>
                </li>
            @endforeach
            {{ $cookies->links() }}
        </ul>

        <br>
        <div class="form" >
          <form action="{{ route('cookie.create') }}" method="POST" enctype="multipart/form-data">
            @csrf
              <div class="space-y-12">

                <div class="border-b border-gray-900/10 pb-12">
                  <h2 class="text-base font-semibold leading-7 text-gray-900">Create Cookie</h2>

                  <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                      <label for="first-name" class="block text-sm font-medium leading-6 text-gray-900">Title</label>
                      <div class="mt-2">
                        <input type="text" name="title" id="title" autocomplete="given-name" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"  value="{{ old('title') }}">
                      </div>
                      @error('title')
                      <p class="text-red-500 text-sm">{{ $message }}</p>
                      @enderror
                    </div>

                    <div class="sm:col-span-3">
                      <label for="last-name" class="block text-sm font-medium leading-6 text-gray-900">Description</label>
                      <div class="mt-2">
                        <input type="text" name="description" id="last-name" autocomplete="family-name" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" value="{{ old('description') }}">
                      </div>
                      @error('description')
                      <p class="text-red-500 text-sm">{{ $message }}</p>
                      @enderror
                    </div>
                  </div>
                </div>

                <div class="mt-10">
                    <label for="image" class="block text-sm font-medium leading-6 text-gray-900">Image</label>
                    <div class="mt-2 flex items-center">
                        <input type="file" name="image" id="image" >
                    </div>
                    @error('image')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>


              <div class="mt-6 flex items-center justify-end gap-x-6">
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Submit</button>
              </div>
            </form>

        </div>
    </div>
@endsection