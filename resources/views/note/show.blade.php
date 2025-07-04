@section('title', 'Note Details')
<x-app-layout>
    <x-slot name="header">
        <div class="row">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 ">
                <div class="note-title text-xl font-semibold text-gray-800 flex-grow-1 me-3">
                    {{ $note->title }}
                </div>

                <div class="d-flex gap-2 flex-shrink-0">
                    <button type="button" class="btn btn-outline-primary toggle-pin-btn"
                        data-note-id="{{ $note->slug }}" data-is-pinned="{{ $note->is_pinned ? 1 : 0 }}"
                        title="{{ $note->is_pinned ? 'Unpin Note' : 'Pin Note' }}">
                        <i class="fa-solid {{ $note->is_pinned ? 'fa-thumbtack-slash' : 'fa-thumbtack' }}"></i>
                    </button>

                    <a class="btn btn-outline-success" href="{{ route('notes.edit', $note->slug) }}" role="button">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </a>

                    <form method="POST" action="{{ route('notes.soft-delete', $note->slug) }}">
                        @csrf
                        @method("DELETE")
                        <button class="btn btn-outline-danger" type="submit">
                            <i class="fa-solid fa-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
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
                        <div class="mb-4">
                            <p style="word-wrap: break-word; font-size: 1rem; color: #333;">
                                {{ $note->content }}
                            </p>
                        </div>

                        @if ($noteImages && $noteImages->count())
                            <div class="mb-4">
                                <h5 class="mb-2"><b> Note Gallery </b></h5>
                                <div class="gallery-grid">
                                    @foreach ($noteImages as $image)
                                        <a href="{{ asset('uploads/notes/' . $image->name) }}" class="glightbox" data-gallery="note-gallery">
                                            <div class="gallery-item">
                                                <img src="{{ asset('uploads/notes/' . $image->name) }}" alt="Note Image">
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="mb-3">
                            <b>Created at:</b> {{ $note->created_at->diffForHumans() }}
                        </div>

                        <div class="mb-3">
                            <b>Last Update at:</b> {{ $note->updated_at->diffForHumans() }}
                        </div>

                        <a href="{{ route('notes.index') }}" class="btn btn-secondary"> Back to Notes</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
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

    <!-- Glightbox JS -->
    <script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
    <script>
        const lightbox = GLightbox({
            selector: '.glightbox'
        });
    </script>
</x-app-layout>
