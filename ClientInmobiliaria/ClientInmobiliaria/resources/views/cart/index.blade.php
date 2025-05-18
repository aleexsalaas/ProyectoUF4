@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4 text-white">Mi Carrito</h1>

    @php
        $total = 0;
    @endphp

    @if(count($cartItems) === 0)
        <p class="text-white">Tu carrito está vacío.</p>
    @else
        <table class="w-full bg-gray-800 text-white rounded-lg overflow-hidden">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Nombre</th>
                    <th class="py-2 px-4 border-b">Precio</th>
                    <th class="py-2 px-4 border-b">Cantidad</th>
                    <th class="py-2 px-4 border-b">Total</th>
                    <th class="py-2 px-4 border-b">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cartItems as $item)
                    @php
                        $price = data_get($item, 'property.price', 0);
                        $quantity = data_get($item, 'quantity', 0);
                        $itemTotal = $price * $quantity;
                        $total += $itemTotal;
                        $propertyId = data_get($item, 'property.id');
                    @endphp

                    @if($propertyId) {{-- Solo mostrar si hay ID válido --}}
                        <tr>
                            <td class="py-2 px-4 border-b">
                                <a href="{{ route('properties.show', $propertyId) }}" class="text-blue-400 hover:underline">
                                    {{ data_get($item, 'property.name', 'Propiedad desconocida') }}
                                </a>
                            </td>
                            <td class="py-2 px-4 border-b">{{ number_format($price, 2) }} €</td>
                            <td class="py-2 px-4 border-b">{{ $quantity }}</td>
                            <td class="py-2 px-4 border-b">{{ number_format($itemTotal, 2) }} €</td>
                            <td class="py-2 px-4 border-b">
                            <form action="{{ route('cart.remove', data_get($item, 'id')) }}" method="POST" onsubmit="return confirm('¿Eliminar del carrito?');" class="inline">
    @csrf
    @method('DELETE')
    <button type="submit" 
        class="bg-red-600 hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 
               text-white px-4 py-2 rounded-md shadow-md transition duration-300 ease-in-out">
        Eliminar
    </button>
</form>

<form action="{{ route('cart.purchase', $propertyId) }}" method="POST" class="inline ml-2">
    @csrf
    <button type="submit" 
        class="bg-green-600 hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-opacity-50 
               text-white px-4 py-2 rounded-md shadow-md transition duration-300 ease-in-out">
        Comprar
    </button>
</form>

                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="py-2 px-4 font-bold border-t text-right">Total:</td>
                    <td colspan="2" class="py-2 px-4 font-bold border-t">{{ number_format($total, 2) }} €</td>
                </tr>
            </tfoot>
        </table>
    @endif
</div>
@endsection
