@extends('layouts.admin')

@section('content')
    <div class="container mt-4">
        <h3 class="text-center">La lista dei miei progetti</h3>
        <div class="text-end">
            <a class="btn btn-success" href="{{ route('admin.projects.create') }}">
                Nuovo Progetto
            </a>
        </div>
        <div class="row justify-content-center">
            <div class="col-8">

                {{-- messaggio per comunicare avvenuta creazione tramite funzione with() --}}
                @if (session('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                @endif

                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Titolo</th>
                            <th scope="col">Tipo</th>

                            <th scope="col">Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($projects as $project)
                            <tr>
                                <th scope="row">{{ $project->title }}</th>
                                <th scope="row">{{ $project->type?->name }}</th>

                                <td>
                                    @if ($project->cover_image)
                                        <img id="image_preview" src="{{ asset('storage/' . $project->cover_image) }}"
                                            alt="project cover" style="max-width:100px">
                                    @else
                                    @endif
                                </td>

                                <td>
                                    <a class="btn btn-success" href="{{ route('admin.projects.show', $project->slug) }}">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>

                                    <a class="btn btn-warning" href="{{ route('admin.projects.edit', $project->slug) }}">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>

                                    <form action="{{ route('admin.projects.destroy', $project->slug) }}" method="POST"
                                        class="d-inline-block">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-danger delete-btn" type="submit"
                                            data-project-title="{{ $project->title }}">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @include('partials.delete-modal')
    </div>
@endsection
