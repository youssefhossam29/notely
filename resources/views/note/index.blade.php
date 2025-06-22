@section('title', 'My Notes')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Notes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Alerts --}}
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

                    {{-- Top Controls --}}
                    <div class="container">
                        <div class="row py-4">
                            <div class="col-md-6 mt-2">
                                <h2><a href="{{ route('notes.index') }}" class="btn btn-outline-dark">Show All Notes</a>
                                </h2>
                            </div>

                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <form method="get" action="{{ route('notes.search') }}">
                                        <div class="input-group">
                                            <input class="form-control" name="search" placeholder="Search..."
                                                value="{{ old('search', $search ?? '') }}">
                                            <button type="submit" class="btn btn-dark">Search</button>
                                        </div>
                                    </form>
                                    @error('search')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>


                    {{-- Notes --}}
                    <div class="container py-6">
                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 custom-cols g-4 notes-container">

                            {{-- Add New Note Card --}}
                            @if (strpos(request()->fullUrl(), 'search') === false)
                                <div class="col add-note-card">
                                    <div
                                        class="card shadow-lg rounded-3 h-100 border-0 d-flex justify-content-center align-items-center text-center">
                                        <a href="{{ route('notes.create') }}" class="text-decoration-none text-muted">
                                            <div>
                                                <div class="display-4 mb-2">+</div>
                                                <h5>Add new note</h5>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            @else
                                {{-- Check Number of Notes --}}
                                @if (count($notes) === 0)
                                    <div
                                        class="m-auto col-lg-6 col-md-12 alert alert-danger text-center d-flex justify-content-center mt-4">
                                        There's no search result for {{ $search }}
                                    </div>
                                @endif


                            @endif

                            {{-- Existing Notes --}}
                            @foreach ($notes as $note)
                                <div class="col note-card" data-is-pinned="{{ $note->is_pinned }}"
                                    data-created-at="{{ $note->created_at }}">
                                    <div class="card shadow-lg rounded-3 h-100 border-0 d-flex flex-column">
                                        <div class="card-body d-flex flex-column p-3">
                                            <div>
                                                <h3 class="card-title mb-2 text-dark note-title">{{ $note->title }}
                                                </h3>
                                                <button class="btn position-absolute top-0 end-0 m-2 p-2 toggle-pin-btn"
                                                    data-note-id="{{ $note->slug }}"
                                                    data-is-pinned="{{ $note->is_pinned }}"
                                                    title="{{ $note->is_pinned ? 'Unpin' : 'Pin' }}">
                                                    <i
                                                        class="fa-solid {{ $note->is_pinned ? 'fa-thumbtack-slash' : 'fa-thumbtack' }}"></i>
                                                </button>
                                                <h5 class="card-title mb-2 text-muted note-content">
                                                    {{ $note->content }}</h5>
                                            </div>

                                            <div class="mt-auto w-100">
                                                <hr class="my-2">
                                                <p class="card-text text-muted small mb-2 mt-3">
                                                    {{ $note->created_at->format('F j, Y') }}</p>
                                                <div class="d-flex justify-content-between gap-2 mt-3">
                                                    <a href="{{ route('notes.show', $note->slug) }}"
                                                        class="btn btn-sm btn-outline-primary flex-fill">
                                                        <i class="fa-solid fa-eye"></i> Show
                                                    </a>
                                                    <a href="{{ route('notes.edit', $note->slug) }}"
                                                        class="btn btn-sm btn-outline-success flex-fill">
                                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                                    </a>
                                                    <a href="#"
                                                        class="btn btn-sm btn-outline-danger delete-btn flex-fill"
                                                        data-bs-toggle="modal" data-bs-target="#deleteNoteModal"
                                                        data-route="{{ route('notes.soft-delete', $note->slug) }}">
                                                        <i class="fa-solid fa-trash"></i> Delete
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {!! $notes->appends(['search' => request('search')])->links('pagination::bootstrap-4') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal -->
    <div class="modal fade" id="deleteNoteModal" tabindex="-1" aria-labelledby="deleteNoteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" id="deleteNoteForm">
                @csrf
                @method('DELETE')

                <div class="modal-content">
                    <div class="modal-header border-0">
                        <h5 class="modal-title">Delete Note?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <p>You are about to move this note to trash.</p>
                    </div>

                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger" id="confirmDeleteBtn">Move to trash</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.delete-btn');
            const deleteForm = document.getElementById('deleteNoteForm');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const deleteUrl = this.dataset.route;
                    deleteForm.action = deleteUrl;
                });
            });

            document.querySelectorAll('.toggle-pin-btn').forEach(button => {
                button.addEventListener('click', function() {
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
                                    'fa-solid fa-thumbtack-slash' : 'fa-solid fa-thumbtack';
                                currentButton.dataset.isPinned = newPinned;
                                currentButton.setAttribute('title', newPinned ? 'Unpin Note' :
                                    'Pin Note');

                                const cardCol = currentButton.closest('.note-card');
                                cardCol.dataset.isPinned = newPinned;
                                reorderCards();
                            } else {
                                alert('Failed to update pin status.');
                            }
                        })
                        .catch(err => {
                            console.error('Error:', err);
                            alert('Something went wrong. Please try again.');
                        });
                });
            });

            function reorderCards() {
                const container = document.querySelector('.notes-container');
                const cards = Array.from(container.querySelectorAll('.note-card'));

                const sortedCards = cards.sort((a, b) => {
                    const pinnedA = parseInt(a.dataset.isPinned);
                    const pinnedB = parseInt(b.dataset.isPinned);
                    const createdA = new Date(a.dataset.createdAt);
                    const createdB = new Date(b.dataset.createdAt);

                    if (pinnedA !== pinnedB) {
                        return pinnedB - pinnedA;
                    }
                    return createdB - createdA;
                });

                sortedCards.forEach(card => container.appendChild(card));
            }
        });
    </script>
</x-app-layout>
