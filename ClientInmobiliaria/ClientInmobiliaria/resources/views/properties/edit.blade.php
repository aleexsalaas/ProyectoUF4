@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-semibold text-gray-800">Editar Propiedad</h1>

        <form action="{{ route('properties.update', $property['id'] ?? $property->id) }}" method="POST" class="mt-6 bg-gray-50 p-6 rounded-lg shadow-lg">
            @csrf
            @method('PATCH')

            <!-- Nombre de la propiedad -->
            <div class="mb-4">
                <label for="name" class="block text-gray-700">Nombre</label>
                <input type="text" id="name" name="name" value="{{ old('name', $property['name'] ?? $property->name) }}" class="mt-2 p-2 w-full border rounded-lg">
                @error('name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Descripción -->
            <div class="mb-4">
                <label for="description" class="block text-gray-700">Descripción</label>
                <textarea id="description" name="description" rows="4" class="mt-2 p-2 w-full border rounded-lg">{{ old('description', $property['description'] ?? $property->description) }}</textarea>
                @error('description')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Precio -->
            <div class="mb-4">
                <label for="price" class="block text-gray-700">Precio</label>
                <input type="number" id="price" name="price" value="{{ old('price', $property['price'] ?? $property->price) }}" class="mt-2 p-2 w-full border rounded-lg">
                @error('price')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Tipo -->
            <div class="mb-4">
                <label for="type" class="block text-gray-700">Tipo</label>
                <select id="type" name="type" class="mt-2 p-2 w-full border rounded-lg">
                    <option value="sale" {{ (old('type', $property['type'] ?? $property->type) == 'sale') ? 'selected' : '' }}>Venta</option>
                    <option value="rent" {{ (old('type', $property['type'] ?? $property->type) == 'rent') ? 'selected' : '' }}>Alquiler</option>
                </select>
                @error('type')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Ubicación -->
            <div class="mb-4">
                <label for="location" class="block text-gray-700">Ubicación</label>
                <input type="text" id="location" name="location" value="{{ old('location', $property['location'] ?? $property->location) }}" class="mt-2 p-2 w-full border rounded-lg">
                @error('location')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Estado -->
            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700">Estado de la propiedad</label>
                <select name="status" id="status" class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    <option value="available" {{ (old('status', $property['status'] ?? $property->status) == 'available') ? 'selected' : '' }}>Disponible</option>
                    <option value="sold" {{ (old('status', $property['status'] ?? $property->status) == 'sold') ? 'selected' : '' }}>Vendido</option>
                    <option value="rented" {{ (old('status', $property['status'] ?? $property->status) == 'rented') ? 'selected' : '' }}>Alquilado</option>
                </select>
                @error('status')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Botón -->
            <div class="mt-6">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-black rounded-lg hover:bg-blue-700">
                    Guardar cambios
                </button>
            </div>
        </form>
    </div>
@endsection
