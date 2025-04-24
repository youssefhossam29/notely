<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Trashed Notes') }}
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
                    @if ( count($notes) == 0)
                        <div class=" m-auto col-lg-6 col-md-12 alert alert-danger text-center d-flex justify-content-center">Trash is empty</div>
                    @else
                        <div class="container">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Note Title</th>
                                        <th scope="col">Note Create at</th>
                                        <th scope="col">Note Deleted at</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 1;
                                    @endphp
                                    @foreach ($notes as $note)
                                        <tr>
                                            <th scope="row">{{$i++}}</th>
                                            <td>{{ $note->title }}</td>
                                            <td>{{ $note->created_at->diffForHumans() }}</td>
                                            <td>{{ $note->deleted_at->diffForHumans() }}</td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-sm">
                                                        <a class="btn btn-success" href="{{ route('note.restore', $note->slug)}}" role="button"><i class="fa-solid fa-arrow-rotate-left"></i> Restore</a>
                                                    </div>

                                                    <div class="col-sm">
                                                        <a class="btn btn-danger" href="{{ route('note.destroy', $note->slug)}}" role="button"><i class="fa-solid fa-trash-can"></i> Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $notes->links('pagination::bootstrap-4') !!}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
