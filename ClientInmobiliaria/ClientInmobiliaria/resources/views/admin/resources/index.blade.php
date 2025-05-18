{{-- resources/views/admin/resources/index.blade.php --}}

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Gestión de Recursos</h1>

        @foreach ($resources as $resource)
            <div>
                <h3>{{ $resource->name }}</h3>
                <p>{{ $resource->description }}</p>
                <!-- Aquí puedes agregar botones o enlaces para editar, eliminar, etc. -->
            </div>
        @endforeach
    </div>
@endsection
