@section('title', 'Note Details')
<x-app-layout>
    <x-slot name="header">
        <div class="row">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 ">
                <div class="note-title text-xl font-semibold text-gray-800 flex-grow-1 me-3">
                    {{ $note->title }}
                </div>

                <div class="d-flex gap-2 flex-shrink-0">
                    <a class="btn btn-outline-success" href="{{ route('note.edit', $note->slug) }}" role="button">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </a>
                    <a class="btn btn-outline-danger" href="{{ route('note.softdelete', $note->slug) }}" role="button">
                        <i class="fa-solid fa-trash"></i> Delete
                    </a>
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
                        <div class="mb-3">
                            {!! $note->content !!}
                        </div>
                        @if ($note->image)
                            <div class="mb-3">
                                {{-- <img src="{{ $note->featured }}" alt="Note Image" style="max-height:300px;"> --}}
                                <img src="{{ URL::asset('uploads/notes/' . $note->image) }}" alt="Note Image"
                                    style="max-height:300px;">
                            </div>
                        @endif

                        <div class="mb-3">
                            Created at: {{ $note->created_at->diffForHumans() }}
                        </div>

                        <div class="mb-3">
                            Last Update at: {{ $note->updated_at->diffForHumans() }}
                        </div>

                        <a href="{{ route('my.notes') }}" class="btn btn-secondary"> Back to Notes</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
