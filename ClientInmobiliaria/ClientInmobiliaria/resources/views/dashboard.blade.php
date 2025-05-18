@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold mb-8 text-white">Tu Dashboard</h1>

        <div class="flex items-center justify-between mb-4">
    <h2 class="text-2xl font-semibold text-white">Propiedades Publicadas</h2>
    <a href="/properties/create"
       class="inline-block bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700 transition">
        + Crear Propiedad
    </a>
</div>


        @forelse($userProperties as $property)
            <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                <h3 class="text-xl font-semibold text-gray-800">{{ $property['name'] }}</h3>
                <p class="text-gray-600 mt-1">{{ $property['description'] }}</p>
                <p class="text-gray-600 mt-1"><strong>Ubicación:</strong> {{ $property['location'] }}</p>
                <p class="text-gray-800 mt-2 text-lg"><strong>Precio:</strong> {{ $property['price'] }} €</p>
                <p class="text-sm mt-2"><strong>Tipo:</strong> {{ ucfirst($property['type']) }}</p>
                <p class="text-sm"><strong>Estado:</strong> {{ ucfirst($property['status']) }}</p>

                <a href="{{ route('properties.show', $property['id']) }}" class="text-blue-600 mt-4 inline-block hover:underline font-medium">
                    Ver propiedad
                </a>
            </div>
        @empty
            <p class="text-white">No has publicado ninguna propiedad todavía.</p>
        @endforelse

        {{-- Sección: Propiedades Compradas --}}
        <h2 class="text-2xl font-semibold text-white mt-10 mb-4">Historial de Propiedades Compradas</h2>

        @forelse($boughtProperties as $property)
            <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                <h3 class="text-xl font-semibold text-gray-800">{{ $property['name'] }}</h3>
                <p class="text-gray-600 mt-1">{{ $property['description'] }}</p>
                <p class="text-gray-600 mt-1"><strong>Ubicación:</strong> {{ $property['location'] }}</p>
                <p class="text-gray-800 mt-2 text-lg"><strong>Precio:</strong> {{ $property['price'] }} €</p>
                <p class="text-sm mt-2"><strong>Tipo:</strong> {{ ucfirst($property['type']) }}</p>
                <p class="text-sm"><strong>Estado:</strong> {{ ucfirst($property['status']) }}</p>

                <a href="{{ route('properties.show', $property['id']) }}" class="text-blue-600 mt-4 inline-block hover:underline font-medium">
                    Ver propiedad
                </a>
            </div>
        @empty
            <p class="text-white">No has comprado ninguna propiedad todavía.</p>
        @endforelse
    </div>
@endsection
