@section('title', 'Edit Note')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit ') . $note->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="container">
                @if ($message = Session::get('success'))
                    <div class="alert alert-primary alert-dismissible fade show" role="alert">
                        {{$message}}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @elseif ($message = Session::get('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{$message}}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="container">
                        <form action="{{ route('note.update', $note->slug) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div style="margin: 15px">
                                <x-input-label for="title" :value="__('Note Title')" />
                                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" required autofocus autocomplete="title"
                                    value="{{ old('title', $note->title) }}" />
                                <x-input-error class="mt-2" :messages="$errors->get('title')" />
                            </div>

                            <div style="margin: 15px">
                                <x-input-label for="content" :value="__('Note Content')" />
                                <x-textarea id="content" name="content" class="mt-1 block w-full" rows="4" autofocus autocomplete="content">
                                    {!! old('content', $note->content) !!}
                                </x-textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('content')" />
                            </div>

                            <div style="margin: 15px">
                                <x-input-label for="image" :value="__('Note Image')" />

                                @if ($note->image)
                                    <div id="image-preview" style="margin-bottom: 10px; margin-top:10px">
                                        <img src="{{ URL::asset('uploads/notes/' . $note->image) }}" alt="Note Image" style="max-height:300px; display:block;">
                                    </div>
                                @endif

                                <div style="display: flex; align-items: center; gap: 20px;">
                                    @if ($note->image)
                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="deleteImage()">
                                            <i class="fa-solid fa-trash"></i> Delete image
                                        </button>
                                    @endif
                                    <label for="file-upload" class="btn btn-outline-primary btn-sm" style="cursor: pointer; padding: 5px 12px;">
                                        <i class="fa-solid fa-upload"></i> {{ $note->image ? "Change Image" : "Upload Image"}}
                                    </label>
                                    <input id="file-upload" type="file" name="image" class="hidden-file-input" style="display:none;" />

                                    <span id="file-name" style="font-size: 0.9rem; color: #555;">No file chosen</span>

                                </div>

                                <input type="hidden" name="delete_image" id="delete_image" value="0" />
                                <x-input-error class="mt-2" :messages="$errors->get('image')" />
                            </div>

                            <button type="submit" class="btn btn-outline-success"> <i class="fa-solid fa-pen-to-square"></i> Update </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function deleteImage() {
            const preview = document.getElementById('image-preview');
            if (preview)
                preview.style.display = 'none';

            document.getElementById('delete_image').value = "delete";
        }

        const fileInput = document.getElementById('file-upload');
        const fileName = document.getElementById('file-name');

        fileInput.addEventListener('change', () => {
            fileName.textContent = fileInput.files.length > 0 ? fileInput.files[0].name : 'No file chosen';
        });
    </script>
</x-app-layout>
