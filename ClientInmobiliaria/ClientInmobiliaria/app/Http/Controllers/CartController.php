<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    private $apiUrl;

    public function __construct()
    {
        $this->apiUrl = env('API_URL');
    }

    private function apiClient()
    {
        $token = Session::get('api_token');
        if (!$token) {
            redirect()->route('login')->send();
            exit;
        }
        return Http::withToken($token);
    }

    private function handleApiError($response)
    {
        if ($response->status() === 401) {
            Session::forget('api_token');
            Session::forget('user');
            redirect()->route('login')->with('error', 'Tu sesión ha expirado, por favor inicia sesión de nuevo.')->send();
            exit;
        }
    }

    public function addToCart($propertyId)
    {
        $response = $this->apiClient()->post("{$this->apiUrl}/api/cart/{$propertyId}");
        $this->handleApiError($response);

        if ($response->successful()) {
            return redirect()->back()->with('success', 'Propiedad añadida al carrito correctamente.');
        }

        $error = $response->json('error') ?? 'No se pudo añadir la propiedad al carrito.';
        return redirect()->back()->with('error', $error);
    }

    public function getCart()
    {
        $response = $this->apiClient()->get("{$this->apiUrl}/api/cart");

        $this->handleApiError($response);

        if ($response->successful()) {
            $cartItems = $response->json();
            return view('cart.index', compact('cartItems'));
        }

        return redirect()->back()->with('error', 'No se pudo cargar el carrito.');
    }

    public function removeFromCart($id)
    {
        $response = $this->apiClient()->delete("{$this->apiUrl}/api/cart/{$id}");

        $this->handleApiError($response);

        if ($response->successful()) {
            return redirect()->back()->with('success', 'Elemento eliminado del carrito correctamente.');
        }

        $error = $response->json('error') ?? 'No se pudo eliminar el elemento del carrito.';
        return redirect()->back()->with('error', $error);
    }
}
