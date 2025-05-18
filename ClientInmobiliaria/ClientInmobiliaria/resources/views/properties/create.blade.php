@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-10 px-6 bg-white shadow-lg rounded-xl mt-10">
    <h1 class="text-3xl font-bold mb-8 text-gray-800 border-b pb-4">Crear Nueva Propiedad</h1>

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-100 text-red-800 rounded">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('properties.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Nombre -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                   required>
            @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Descripción -->
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
            <textarea name="description" id="description"
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                      required>{{ old('description') }}</textarea>
            @error('description') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Precio -->
        <div>
            <label for="price" class="block text-sm font-medium text-gray-700">Precio (€)</label>
            <input type="number" name="price" id="price" value="{{ old('price') }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                   required>
            @error('price') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Tipo -->
        <div>
            <label for="type" class="block text-sm font-medium text-gray-700">Tipo</label>
            <select name="type" id="type"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    required>
                <option value="sale" {{ old('type') == 'sale' ? 'selected' : '' }}>Venta</option>
                <option value="rent" {{ old('type') == 'rent' ? 'selected' : '' }}>Alquiler</option>
            </select>
            @error('type') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Ubicación -->
        <div>
            <label for="location" class="block text-sm font-medium text-gray-700">Ubicación</label>
            <input type="text" name="location" id="location" value="{{ old('location') }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                   required>
            @error('location') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Estado -->
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700">Estado</label>
            <select name="status" id="status"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    required>
                <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Disponible</option>
                <option value="sold" {{ old('status') == 'sold' ? 'selected' : '' }}>Vendido</option>
                <option value="rented" {{ old('status') == 'rented' ? 'selected' : '' }}>Alquilado</option>
            </select>
            @error('status') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Botón -->
        <div class="pt-4">
            <button type="submit"
                    class="w-full bg-blue-600 text-white font-semibold py-3 rounded-lg hover:bg-blue-700 transition duration-200">
                Guardar Propiedad
            </button>
        </div>
    </form>
</div>
@endsection
