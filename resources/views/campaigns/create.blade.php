@extends('layouts.business')

@section('title', __('Create campaign'))

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">{{ __('Create campaign') }}</h1>
        <p class="mt-1 text-gray-500">{{ __('Fill in the details below. Image is required.') }}</p>
    </div>

    <div class="max-w-2xl rounded-2xl bg-white p-6 sm:p-8 shadow border border-gray-100">
        <form method="POST" action="{{ route('business.campaigns.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">{{ __('Title') }}</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required maxlength="255"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">{{ __('Description') }}</label>
                <textarea name="description" id="description" rows="5" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="category" class="block text-sm font-medium text-gray-700">{{ __('Category') }}</label>
                <select name="category" id="category" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">{{ __('Select a category') }}</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}" @selected(old('category') === $cat)>{{ $cat }}</option>
                    @endforeach
                </select>
                @error('category')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="expiry_date" class="block text-sm font-medium text-gray-700">{{ __('Expiry date') }}</label>
                <input type="date" name="expiry_date" id="expiry_date" value="{{ old('expiry_date') }}" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('expiry_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="image" class="block text-sm font-medium text-gray-700">{{ __('Campaign image') }}</label>
                <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/jpg" required
                    class="mt-1 block w-full text-sm text-gray-600 file:mr-4 file:rounded-md file:border-0 file:bg-emerald-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-emerald-700 hover:file:bg-emerald-100">
                <p class="mt-1 text-xs text-gray-500">{{ __('JPG or PNG, max 2 MB.') }}</p>
                @error('image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-3 pt-4">
                <a href="{{ route('business.campaigns.index') }}"
                    class="text-sm font-medium text-gray-600 hover:text-gray-900">{{ __('Cancel') }}</a>
                <button type="submit"
                    class="inline-flex justify-center rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                    {{ __('Save campaign') }}
                </button>
            </div>
        </form>
    </div>
@endsection
