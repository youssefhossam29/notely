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

            {{-- Form --}}
            <div class="bg-white overflow-hidden shadow sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form id="edit-form" action="{{ route('notes.update', $note->slug) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Title --}}
                        <div class="mb-4">
                            <x-input-label for="title" :value="__('Note Title')" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full"
                                required autofocus autocomplete="title" value="{{ old('title', $note->title) }}" />
                            <x-input-error class="mt-2" :messages="$errors->get('title')" />
                        </div>

                        {{-- Content --}}
                        <div class="mb-4">
                            <x-input-label for="content" :value="__('Note Content')" />
                            <x-textarea id="content" name="content" class="mt-1 block w-full" rows="4" autofocus
                                autocomplete="content">{{ old('content', $note->content) }}</x-textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('content')" />
                        </div>

                        {{-- Note Gallery --}}
                        <div class="mb-4">
                            <x-input-label class="mb-2" for="images" :value="__('Note Gallery')" />

                            {{-- Existing Images --}}
                            @if ($noteImages && $noteImages->count())
                                <div class="mb-4">
                                    <div class="gallery-grid d-flex flex-wrap gap-3">
                                        @foreach ($noteImages as $image)
                                            <div class="gallery-item position-relative border rounded p-1"
                                                style="width: 150px; height: 150px; overflow: hidden;">
                                                <img src="{{ asset('uploads/notes/' . $image->name) }}" alt="Note Image"
                                                    style="width: 100%; height: 100%; object-fit: cover;">

                                                <button type="button"
                                                    class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 delete-image-btn"
                                                    data-image-id="{{ $image->id }}" title="Delete this image">
                                                    <i class="fa-solid fa-xmark"></i>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- Upload New Images --}}
                            <label for="file-upload" class="btn btn-outline-primary btn-sm"
                                style="cursor: pointer; padding: 5px 12px;">
                                <i class="fa-solid fa-upload"></i> {{ 'Upload new images' }}
                            </label>
                            <input id="file-upload" type="file" name="images[]" multiple class="hidden-file-input"
                                style="display: none;" />
                            <span id="file-name" class="d-block mt-2 text-sm text-muted">No file chosen</span>

                            {{-- Errors --}}
                            @foreach ($errors->get('images.*') as $imageErrors)
                                <x-input-error class="mt-2" :messages="$imageErrors" />
                            @endforeach
                        </div>

                        {{-- Submit --}}
                        <div class="text-end">
                            <button type="submit" class="btn btn-outline-success me-3">
                                <i class="fa-solid fa-pen-to-square"></i> Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script>

        // File name preview
        document.getElementById('file-upload').addEventListener('change', function () {
            const fileNameSpan = document.getElementById('file-name');
            fileNameSpan.innerText = this.files.length ?
                Array.from(this.files).map(f => f.name).join(', ') :
                'No file chosen';
        });

        // Delete image button
        document.querySelectorAll('.delete-image-btn').forEach(button => {
            button.addEventListener('click', function () {
                const imageId = this.dataset.imageId;
                const imageItem = this.closest('.gallery-item');

                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'delete_images[]';
                hiddenInput.value = imageId;

                const form = document.getElementById('edit-form');
                form.appendChild(hiddenInput);

                imageItem.remove();
            });
        });

        // toggle pin button
        document.addEventListener('DOMContentLoaded', function () {
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
