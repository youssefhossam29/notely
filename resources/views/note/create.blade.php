<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Note') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="container">
                @if ($message = Session::get('success'))
                    <div class="alert alert-primary" role="alert">
                        {{$message}}
                    </div>
                @elseif ($message = Session::get('error'))
                    <div class="alert alert-danger" role="alert">
                        {{$message}}
                    </div>
                @endif
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="container">
                        <form action="{{route('note.store')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('POST')
                            <div style="margin: 15px">
                                <x-input-label for="title" :value="__('Note Title')" />
                                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full"  required autofocus autocomplete="title" value="{{old('title')}}" />
                                <x-input-error class="mt-2" :messages="$errors->get('title')" />
                            </div>
                            <div style="margin: 15px">
                                <x-input-label for="content" :value="__('Note Content')" />
                                <x-textarea id="content" name="content" class="mt-1 block w-full" rows="3" autofocus autocomplete="content">
                                    {!! old('content') !!}
                                </x-textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('content')" />
                            </div>
                            <div style="margin: 15px">
                                <x-input-label for="image" :value="__('Note Image')" />
                                <x-file-input class="mt-1 block w-full" name="image" />
                                <x-input-error class="mt-2" :messages="$errors->get('image')" />
                            </div>

                            <div style="margin: 15px">
                                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-square-plus"></i> Create</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
