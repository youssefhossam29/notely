@section('title', 'Create Note')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Note') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="container">
                @if ($message = Session::get('success'))
                    <div class="alert alert-primary alert-dismissible fade show" role="alert">
                        {{ $message }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @elseif ($message = Session::get('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ $message }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>
            <div class="bg-white overflow-hidden shadow sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="container">
                        <form action="{{ route('notes.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('POST')
                            <div style="margin: 15px">
                                <x-input-label for="title" :value="__('Note Title')" />
                                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full"
                                     autofocus autocomplete="title" value="{{ old('title') }}" />
                                <x-input-error class="mt-2" :messages="$errors->get('title')" />
                            </div>
                            <div style="margin: 15px">
                                <x-input-label for="content" :value="__('Note Content')" />
                                <x-textarea id="content" name="content" class="mt-1 block w-full" rows="3"
                                    autofocus autocomplete="content">
                                    {!! old('content') !!}
                                </x-textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('content')" />
                            </div>
                            <div style="margin: 15px">
                                <x-input-label class="mb-2" for="image" :value="__('Note Image')" />
                                <label for="file-upload" class="btn btn-outline-primary btn-sm"
                                    style="cursor: pointer; padding: 5px 12px;">
                                    <i class="fa-solid fa-upload"></i> {{ 'Upload Image' }}
                                </label>
                                <input id="file-upload" type="file" name="image" class="hidden-file-input"
                                    style="display:none;" />
                                <span id="file-name" style="font-size: 0.9rem; color: #555;">No file chosen</span>
                                <x-input-error class="mt-2" :messages="$errors->get('image')" />
                            </div>

                            <div style="margin: 15px">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_pinned" name="is_pinned"
                                        {{ old('is_pinned') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_pinned">
                                        Pin Note
                                    </label>
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-outline-dark" style="margin: 0 15px 0 0">
                                    <i class="fa-solid fa-plus"></i> Create
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        const fileInput = document.getElementById('file-upload');
        const fileName = document.getElementById('file-name');

        fileInput.addEventListener('change', () => {
            fileName.textContent = fileInput.files.length > 0 ? fileInput.files[0].name : 'No file chosen';
        });
    </script>
</x-app-layout>
