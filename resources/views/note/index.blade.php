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

            {{-- Check Number of Notes --}}
            @if (count($notes) === 0)
                <div class="m-auto col-lg-6 col-md-12 alert alert-danger text-center d-flex justify-content-center">
                    There is no notes
                </div>
            @else
                <div class="bg-white overflow-hidden shadow sm:rounded-lg">
                    <div class="p-6 text-gray-900">

                        {{-- Top Controls --}}
                        <div class="container">
                            <div class="row py-4">
                                <div class="col-md-6 mt-2">
                                    <h2><a href="{{ route('my.notes') }}" class="btn btn-outline-dark">Show All Notes</a></h2>
                                </div>

                                <div class="col-md-6 mt-2">
                                    <div class="form-group">
                                        <form method="get" action="{{ route('note.search') }}">
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
                            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 custom-cols g-4">
                                {{-- Add New Note Card --}}
                                <div class="col">
                                    <div class="card shadow-lg rounded-3 h-100 border-0 d-flex justify-content-center align-items-center text-center">
                                        <a href="{{ route('note.create') }}" class="text-decoration-none text-muted">
                                            <div>
                                                <div class="display-4 mb-2">+</div>
                                                <h5>Add new note</h5>
                                            </div>
                                        </a>
                                    </div>
                                </div>

                                {{-- Existing Notes --}}
                                @foreach ($notes as $note)
                                    <div class="col">
                                        <div class="card shadow-lg rounded-3 h-100 border-0 d-flex flex-column">
                                            <div class="card-body d-flex flex-column p-3">
                                                <div>
                                                    <h3 class="card-title mb-2 text-dark note-title">{{ $note->title }}
                                                    </h3>
                                                    <h5 class="card-title mb-2 text-muted note-content">
                                                        {{ $note->content }}
                                                    </h5>
                                                </div>

                                                {{-- Footer: always at the bottom --}}
                                                <div class="mt-auto w-100">
                                                    <hr class="my-2">
                                                    <p class="card-text text-muted small mb-2 mt-3">
                                                        {{ $note->created_at->format('F j, Y') }}
                                                    </p>
                                                    <div class="d-flex justify-content-between gap-2 mt-3">
                                                        <a href="{{ route('note.show', $note->slug) }}"
                                                            class="btn btn-sm btn-outline-primary flex-fill">
                                                            <i class="fa-solid fa-eye"></i> Show
                                                        </a>
                                                        <a href="{{ route('note.edit', $note->slug) }}"
                                                            class="btn btn-sm btn-outline-success flex-fill">
                                                            <i class="fa-solid fa-pen-to-square"></i> Edit
                                                        </a>
                                                        <a href="#"
                                                            class="btn btn-sm btn-outline-danger delete-btn flex-fill"
                                                            data-bs-toggle="modal" data-bs-target="#deleteNoteModal"
                                                            data-route="{{ route('note.softdelete', $note->slug) }}">
                                                            <i class="fa-solid fa-trash"></i> Delete
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Pagination --}}
                            <div class="d-flex justify-content-center mt-4">
                                {!! $notes->links('pagination::bootstrap-4') !!}
                            </div>
                        </div>
            @endif
        </div>
    </div>
    </div>
    </div>

    {{-- Delete Modal --}}
    <div class="modal fade" id="deleteNoteModal" tabindex="-1">
        <div class="modal-dialog">
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
                    <a id="confirmDeleteBtn" href="#" class="btn btn-danger">Move to trash</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Logic --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.delete-btn');
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const deleteUrl = this.dataset.route;
                    confirmDeleteBtn.href = deleteUrl;
                });
            });
        });
    </script>
</x-app-layout>
