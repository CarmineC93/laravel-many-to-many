@extends('layouts.admin')

@section('content')
    <div class="container">
        <h2 class="text-center mt-3">Crea un nuovo progetto</h2>
        <div class="row justify-content-center">
            <div class="col-8">

                {{-- mostro in pagina gli errori --}}
                @include('partials.errors')

                <form action="{{ route('admin.projects.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="title">Titolo</label>
                        <input type="text" id="title" name="title"
                            class="form-control @error('title')
                        is-invalid
                        @enderror"
                            value="{{ old('title') }}">
                        @error('title')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="type">Categoria</label>
                        {{-- il 'name' del select deve essere come il nome della colonna --}}
                        <select name="type_id" id="type" class="form-select">
                            <option value="">Nessun tipo</option>
                            @foreach ($types as $type)
                                <option value="{{ $type->id }}" @selected(old('type_id') == $type->id)>{{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="cover_image">Immagine</label>
                        <input type="file" name="cover_image" id="cover_image"
                            class="form-control
                            @error('cover_image')
                            is-invalid
                            @enderror">
                        @error('cover_image')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- anteprima immagine che si aggiorna tramit attributo id colllegato ad app.js --}}
                    <div class="mt-3">
                        <img id="image_preview" src="" alt="" style="max-height: 200px">
                    </div>

                    <div class="form-group mb-3">
                        <label for="content">Descrizione</label>
                        <textarea name="content" id="content" rows="10"
                            class="form-control @error('content')
                        is-invalid
                        @enderror">{{ old('content') }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <button class="btn btn-success" type="submit">Salva</button>
                </form>
            </div>
        </div>
    </div>
@endsection
