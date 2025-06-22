@section('title', 'Edit Note')
<x-app-layout>
    <x-slot name="header">
        <div class="note-title text-xl font-semibold text-gray-800 flex-grow-1 me-3">
            {{ __('Edit ') . $note->title }}
            <button type="button" class="btn btn-outline-primary toggle-pin-btn ms-3" data-note-id="{{ $note->slug }}"
                data-is-pinned="{{ $note->is_pinned ? 1 : 0 }}"
                title="{{ $note->is_pinned ? 'Unpin Note' : 'Pin Note' }}">
                <i class="fa-solid {{ $note->is_pinned ? 'fa-thumbtack-slash' : 'fa-thumbtack' }}"></i>
            </button>
        </div>
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
                        <form action="{{ route('notes.update', $note->slug) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div style="margin: 15px">
                                <x-input-label for="title" :value="__('Note Title')" />
                                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full"
                                    required autofocus autocomplete="title" value="{{ old('title', $note->title) }}" />
                                <x-input-error class="mt-2" :messages="$errors->get('title')" />
                            </div>

                            <div style="margin: 15px">
                                <x-input-label for="content" :value="__('Note Content')" />
                                <x-textarea id="content" name="content" class="mt-1 block w-full" rows="4"
                                    autofocus autocomplete="content">
                                    {!! old('content', $note->content) !!}
                                </x-textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('content')" />
                            </div>

                            <div style="margin: 15px">
                                <x-input-label class="mb-2" for="image" :value="__('Note Image')" />

                                @if ($note->image)
                                    <div id="image-preview" style="margin-bottom: 10px; margin-top:10px">
                                        <img src="{{ URL::asset('uploads/notes/' . $note->image) }}" alt="Note Image"
                                            style="max-height:300px; display:block;">
                                    </div>
                                @endif

                                <div class="btn-container" style="display: flex; align-items: center; gap: 20px;">
                                    @if ($note->image)
                                        <button type="button" class="btn btn-outline-danger btn-sm"
                                            onclick="deleteImage()">
                                            <i class="fa-solid fa-trash"></i> Delete image
                                        </button>
                                    @endif
                                    <label for="file-upload" class="btn btn-outline-primary btn-sm"
                                        style="cursor: pointer; padding: 5px 12px;">
                                        <i class="fa-solid fa-upload"></i>
                                        {{ $note->image ? 'Change Image' : 'Upload Image' }}
                                    </label>
                                    <input id="file-upload" type="file" name="image" class="hidden-file-input"
                                        style="display:none;" />

                                    <span id="file-name" style="font-size: 0.9rem; color: #555;">No file chosen</span>

                                </div>

                                <input type="hidden" name="delete_image" id="delete_image" value="0" />
                                <x-input-error class="mt-2" :messages="$errors->get('image')" />
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-outline-success" style="margin: 0 15px 0 0">
                                    <i class="fa-solid fa-pen-to-square"></i> Update
                                </button>
                            </div>
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

        document.addEventListener('DOMContentLoaded', function() {
            const pinButton = document.querySelector('.toggle-pin-btn');

            if (pinButton) {
                pinButton.addEventListener('click', function() {
                    const noteSlug = this.dataset.noteId;
                    const isPinned = this.dataset.isPinned == "1" ? 1 : 0;
                    const newPinned = isPinned === 1 ? 0 : 1;
                    const currentButton = this;

                    const formData = new FormData();
                    formData.append('is_pinned', newPinned);
                    formData.append('_token', '{{ csrf_token() }}');
                    formData.append('_method', 'PUT');

                    fetch(`/notes/${noteSlug}/toggle-pin`, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => {
                            if (!response.ok) throw new Error('Server error');
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                const icon = currentButton.querySelector('i');
                                icon.className = newPinned === 1 ?
                                    'fa-solid fa-thumbtack-slash' :
                                    'fa-solid fa-thumbtack';

                                currentButton.dataset.isPinned = newPinned;
                                currentButton.setAttribute('title', newPinned ? 'Unpin Note' :
                                    'Pin Note');
                            } else {
                                alert('Failed to update pin status.');
                            }
                        })
                        .catch(err => {
                            console.error('Error:', err);
                            alert('Something went wrong. Please try again.');
                        });
                });
            }
        });
    </script>
</x-app-layout>
