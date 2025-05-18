@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

    <h1 class="text-3xl font-bold mb-6">{{ $property['name'] ?? 'Propiedad' }}</h1>

    <div class="bg-white shadow-md rounded-lg p-4 mb-4">
        <p class="text-sm text-gray-600">{{ $property['description'] ?? '' }}</p>
        <p class="text-lg font-medium mt-2">{{ $property['price'] ?? '' }} €</p>
        <p class="text-sm mt-2"><strong>Tipo:</strong> {{ ucfirst($property['type'] ?? '') }}</p>
        <p class="text-sm mt-2"><strong>Estado:</strong> {{ ucfirst($property['status'] ?? '') }}</p>
        <p class="text-sm mt-2"><strong>Ubicación:</strong> {{ ucfirst($property['location'] ?? '') }}</p>
        <p class="text-sm mt-2"><strong>Propietario:</strong> {{ $property['user']['name'] ?? 'Desconocido' }}</p>

        {{-- Botones Comprar y Añadir al carrito --}}
        @if($property['status'] === 'available' && $user['id'] !== $property['user']['id'])
            <div class="flex space-x-4 mt-4">
                <form method="POST" action="{{ route('properties.buy', $property['id']) }}">
                    @csrf
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Comprar
                    </button>
                </form>

                <form method="POST" action="{{ route('cart.add', $property['id']) }}">
                    @csrf
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                        Añadir al carrito
                    </button>
                </form>
            </div>
        @else
            <p class="text-sm text-gray-600 mt-4">No puedes comprar esta propiedad.</p>
        @endif
    </div>

    <a href="{{ route('properties.index') }}" class="text-blue-500 mt-4 inline-block">← Volver a propiedades</a>

    <!-- Mensajes flash -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mt-6" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mt-6" role="alert">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Comentarios -->
    <div class="bg-white shadow-md rounded-lg p-4 mt-6">
        <h2 class="text-2xl font-bold">Comentarios</h2>

        {{-- Mostrar formulario solo si está logueado --}}
        @if($user && $api_token)
            <form method="POST" action="{{ route('reviews.store', ['propertyId' => $property['id'] ?? 0]) }}" class="mt-4">
                @csrf
                <div class="mb-4">
                    <label for="comment" class="block font-bold">Tu Comentario:</label>
                    <textarea name="comment" id="comment" class="w-full border rounded p-2" required>{{ old('comment') }}</textarea>
                </div>
                <div class="mb-4">
                    <label for="rating" class="block font-bold">Valoración (1-5):</label>
                    <input type="number" name="rating" id="rating" min="1" max="5" class="w-full border rounded p-2" value="{{ old('rating') }}" required>
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Enviar Comentario</button>
            </form>
        @else
            <p class="mt-4 text-gray-600">Debes <a href="{{ route('login') }}" class="text-blue-500 underline">iniciar sesión</a> para dejar un comentario.</p>
        @endif

        {{-- Lista de comentarios --}}
        <div class="mt-6">
            @forelse($reviews as $review)
                <div class="bg-gray-100 p-4 rounded mt-4">
                    <p class="text-lg font-semibold">{{ $review['user']['name'] ?? 'Anónimo' }}</p>
                    <p class="text-gray-700">{{ $review['comment'] ?? '' }}</p>
                    <p class="text-sm text-gray-500">Valoración: {{ $review['rating'] ?? '' }} ⭐</p>
                    <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($review['created_at'])->translatedFormat('l j \\d\\e F \\d\\e Y, H:i') }}</p>
                </div>
            @empty
                <p>No hay comentarios aún.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
