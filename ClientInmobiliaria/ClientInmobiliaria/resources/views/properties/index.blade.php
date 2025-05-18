@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold mb-6 text-white">Propiedades</h1>


        @forelse($properties as $property)
            <div class="bg-white shadow-md rounded-lg p-4 mb-4">
                <h2 class="text-2xl font-semibold">{{ $property['name'] }}</h2>
                <p class="text-sm text-gray-600">{{ $property['description'] }}</p>
                <p class="text-sm text-gray-600">{{ $property['location'] }}</p>
                <p class="text-lg font-medium mt-2">{{ $property['price'] }} €</p>
                <p class="text-sm mt-2"><strong>Tipo:</strong> {{ ucfirst($property['type']) }}</p>
                <p class="text-sm mt-2"><strong>Estado:</strong> {{ ucfirst($property['status']) }}</p>

                <a href="{{ route('properties.show', $property['id']) }}" class="text-blue-500 mt-4 inline-block hover:underline">
                    Ver propiedad
                </a>
            </div>
        @empty
            <p>No hay propiedades disponibles en este momento.</p>
        @endforelse
    </div>
@endsection
