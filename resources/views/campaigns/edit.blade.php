@extends('layouts.business')

@section('title', __('Edit campaign'))

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">{{ __('Edit campaign') }}</h1>
        <p class="mt-1 text-gray-500">{{ __('Update details or replace the image.') }}</p>
    </div>

    <div class="max-w-2xl rounded-2xl bg-white p-6 sm:p-8 shadow border border-gray-100">
        <form method="POST" action="{{ route('business.campaigns.update', $campaign) }}" enctype="multipart/form-data"
            class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">{{ __('Title') }}</label>
                <input type="text" name="title" id="title" value="{{ old('title', $campaign->title) }}" required
                    maxlength="255"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">{{ __('Description') }}</label>
                <textarea name="description" id="description" rows="5" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $campaign->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="category" class="block text-sm font-medium text-gray-700">{{ __('Category') }}</label>
                <select name="category" id="category" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}" @selected(old('category', $campaign->category) === $cat)>{{ $cat }}</option>
                    @endforeach
                </select>
                @error('category')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="expiry_date" class="block text-sm font-medium text-gray-700">{{ __('Expiry date') }}</label>
                <input type="date" name="expiry_date" id="expiry_date"
                    value="{{ old('expiry_date', $campaign->expiry_date?->format('Y-m-d')) }}" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('expiry_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <p class="block text-sm font-medium text-gray-700">{{ __('Current image') }}</p>
                @if ($campaign->image)
                    <div class="mt-2 overflow-hidden rounded-lg border border-gray-200 bg-gray-50 max-w-md">
                        <img src="{{ asset('storage/'.$campaign->image) }}" alt="{{ $campaign->title }}"
                            class="w-full object-cover max-h-56">
                    </div>
                @else
                    <p class="mt-2 text-sm text-gray-500">{{ __('No image on file.') }}</p>
                @endif
                <label for="image" class="mt-4 block text-sm font-medium text-gray-700">{{ __('Replace image (optional)') }}</label>
                <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/jpg"
                    class="mt-1 block w-full text-sm text-gray-600 file:mr-4 file:rounded-md file:border-0 file:bg-emerald-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-emerald-700 hover:file:bg-emerald-100">
                <p class="mt-1 text-xs text-gray-500">{{ __('JPG or PNG, max 2 MB. Leave empty to keep the current image.') }}</p>
                @error('image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-3 pt-4">
                <a href="{{ route('business.campaigns.index') }}"
                    class="text-sm font-medium text-gray-600 hover:text-gray-900">{{ __('Cancel') }}</a>
                <button type="submit"
                    class="inline-flex justify-center rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                    {{ __('Update campaign') }}
                </button>
            </div>
        </form>
    </div>
@endsection
