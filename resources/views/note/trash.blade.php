@section('title', 'Trashed Notes')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Trashed Notes') }}
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
                <div class=" m-auto col-lg-6 col-md-12 alert alert-danger text-center d-flex justify-content-center">
                    Trash is empty</div>
            @else
                <div class="bg-white overflow-hidden shadow sm:rounded-lg">
                    <div class="p-6 text-gray-900">

                        {{-- Top Controls --}}
                        <div class="container">
                            <div class="row py-4">
                                <div class="col-md-6 mb-4 mt-2">
                                    <h2><a href="{{ route('my.notes') }}" class="btn btn-outline-dark">Show All
                                            Notes</a></h2>
                                </div>
                                <hr>

                                {{-- Notes --}}
                                <div class="container py-6">
                                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 custom-cols g-4">
                                        @foreach ($notes as $note)
                                            <div class="col">
                                                <div class="card shadow-lg rounded-3 h-100 border-0 d-flex flex-column">
                                                    <div class="card-body d-flex flex-column p-3">
                                                        <div>
                                                            <h3 class="card-title mb-2 text-dark note-title">
                                                                {{ $note->title }}
                                                            </h3>
                                                            <h5 class="card-title mb-2 text-muted note-content">
                                                                {{ $note->content }}
                                                            </h5>
                                                        </div>

                                                        {{-- Footer: always at the bottom --}}
                                                        <div class="mt-auto w-100">
                                                            <hr class="my-2">
                                                            <p class="card-text text-muted small mb-2 mt-3">
                                                                <span>Created: {{ $note->created_at->format('F j, Y') }}</span><br>
                                                                <span class="text-danger">Deleted: {{ $note->deleted_at->format('F j, Y') }}</span>
                                                            </p>
                                                            <div class="d-flex justify-content-between gap-2 mt-3">
                                                                <a href="{{ route('note.restore', $note->slug) }}"
                                                                    class="btn btn-sm btn-outline-secondary flex-fill">
                                                                    <i class="fa-solid fa-arrow-rotate-left"></i> Restore
                                                                </a>

                                                                <button type="button"
                                                                class="btn btn-sm btn-outline-danger delete-btn flex-fill"
                                                                data-bs-toggle="modal" data-bs-target="#deleteNoteModal"
                                                                data-route="{{ route('note.destroy', $note->slug) }}">
                                                                <i class="fa-solid fa-trash-can"></i> Delete
                                                            </button>
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
                    <p>You are about to delete this note forever.</p>
                    <p>This action can't be reversed.</p>
                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>

                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete Note</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.delete-btn');
            const deleteForm = document.getElementById('deleteForm');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const deleteUrl = this.dataset.route;
                    deleteForm.action = deleteUrl;
                });
            });
        });
    </script>

</x-app-layout>
