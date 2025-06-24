@section('title', 'Create Note')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Note') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Flash Messages --}}
            <div class="container mb-4">
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

            {{-- Form Card --}}
            <div class="bg-white overflow-hidden shadow sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="container">
                        <form action="{{ route('notes.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('POST')

                            {{-- Note Title --}}
                            <div class="mb-4">
                                <x-input-label for="title" :value="__('Note Title')" />
                                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full"
                                    required autofocus autocomplete="title" value="{{ old('title') }}" />
                                <x-input-error class="mt-2" :messages="$errors->get('title')" />
                            </div>

                            {{-- Note Content --}}
                            <div class="mb-4">
                                <x-input-label for="content" :value="__('Note Content')" />
                                <x-textarea id="content" name="content" class="mt-1 block w-full" rows="3"
                                    autofocus autocomplete="content">{{ old('content') }}</x-textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('content')" />
                            </div>

                            {{-- Note Gallery --}}
                            <div class="mb-4">
                                <x-input-label class="mb-2" for="images" :value="__('Note Gallery')" />
                                <label for="file-upload" class="btn btn-outline-primary btn-sm"
                                    style="cursor: pointer; padding: 5px 12px;">
                                    <i class="fa-solid fa-upload"></i> {{ 'Upload Images' }}
                                </label>
                                <input id="file-upload" type="file" name="images[]" multiple
                                    class="hidden-file-input" style="display:none;" />
                                <span id="file-name" class="d-block mt-2 text-sm text-muted">No file chosen</span>

                                {{-- Error Messages --}}
                                @foreach ($errors->get('images.*') as $imageErrors)
                                    <x-input-error class="mt-2" :messages="$imageErrors" />
                                @endforeach
                            </div>

                            {{-- Pinned Checkbox --}}
                            <div class="mb-4 form-check">
                                <input class="form-check-input" type="checkbox" id="is_pinned" name="is_pinned"
                                    {{ old('is_pinned') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_pinned">
                                    {{ __('Pin Note') }}
                                </label>
                            </div>

                            {{-- Submit --}}
                            <div class="text-end">
                                <button type="submit" class="btn btn-outline-dark me-3">
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
        document.getElementById('file-upload').addEventListener('change', function() {
            const fileNameSpan = document.getElementById('file-name');
            fileNameSpan.innerText = this.files.length ?
                Array.from(this.files).map(f => f.name).join(', ') :
                'No file chosen';
        });
    </script>

</x-app-layout>
