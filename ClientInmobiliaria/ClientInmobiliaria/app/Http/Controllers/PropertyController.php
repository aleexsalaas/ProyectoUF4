<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class PropertyController extends Controller
{
    private $apiUrl;

    public function __construct()
    {
        $this->apiUrl = env('API_URL');
    }

    // Método privado para reutilizar la configuración HTTP con token
    private function apiClient()
    {
        $token = Session::get('api_token');
        if (!$token) {
            // No hay token, redirige a login
            redirect()->route('login')->send();
            exit;
        }
        return Http::withToken($token);
    }

    // Método privado para manejar respuestas no exitosas y 401
    private function handleApiError($response)
    {
        if ($response->status() === 401) {
            Session::forget('api_token');
            Session::forget('user');
            redirect()->route('login')->with('error', 'Tu sesión ha expirado, por favor inicia sesión de nuevo.')->send();
            exit;
        }
    }

    public function index()
    {
        $response = $this->apiClient()->get("{$this->apiUrl}/api/properties");

        $this->handleApiError($response);

        if ($response->successful()) {
            $properties = $response->json();
            return view('properties.index', compact('properties'));
        }

        return redirect()->back()->with('error', 'No se pudo cargar la lista de propiedades.');
    }

    public function show($id)
    {
        $propertyResponse = $this->apiClient()->get("{$this->apiUrl}/api/properties/{$id}");
        $this->handleApiError($propertyResponse);
    
        $reviewsResponse = $this->apiClient()->get("{$this->apiUrl}/api/properties/{$id}/reviews");
        $this->handleApiError($reviewsResponse);
    
        if ($propertyResponse->successful()) {
            $property = $propertyResponse->json();
            $reviews = $reviewsResponse->successful() ? collect($reviewsResponse->json()) : collect();
    
            // Obtener user y token de sesión (si existen)
            $user = session('user', null);
            $api_token = session('api_token', null);
    
            return view('properties.show', compact('property', 'reviews', 'user', 'api_token'));
        }
    
        return redirect()->route('properties.index')->with('error', 'No se pudo cargar la propiedad.');
    }
    

    public function create()
    {
        $this->apiClient(); // Esto forzará la validación del token
    
        return view('properties.create');
    }
    

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'type' => 'required|in:sale,rent',
            'location' => 'required',
            'status' => 'required|in:available,sold,rented',
        ]);

        $response = $this->apiClient()->post("{$this->apiUrl}/api/properties", $request->only(['name', 'description', 'price', 'type', 'location', 'status']));

        $this->handleApiError($response);

        if ($response->status() === 201) {
            return redirect()->route('properties.index')->with('success', 'Propiedad creada exitosamente.');
        }

        $error = $response->json('error') ?? 'Error al crear la propiedad.';
        return redirect()->back()->withInput()->with('error', $error);
    }

    public function edit($id)
    {
        $response = $this->apiClient()->get("{$this->apiUrl}/api/properties/{$id}");

        $this->handleApiError($response);

        if ($response->successful()) {
            $property = $response->json();
            return view('properties.edit', compact('property'));
        }

        return redirect()->route('properties.index')->with('error', 'Propiedad no encontrada.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'type' => 'required|in:sale,rent',
            'location' => 'required',
            'status' => 'required|in:available,sold,rented',
        ]);

        $response = $this->apiClient()->put("{$this->apiUrl}/api/properties/{$id}", $request->only(['name', 'description', 'price', 'type', 'location', 'status']));

        $this->handleApiError($response);

        if ($response->successful()) {
            return redirect()->route('dashboard')->with('success', 'Propiedad actualizada correctamente.');
        }

        $error = $response->json('error') ?? 'Error al actualizar la propiedad.';
        return redirect()->back()->withInput()->with('error', $error);
    }

    public function destroy(Request $request, $id)
    {
        $response = $this->apiClient()->delete("{$this->apiUrl}/api/properties/{$id}");

        $this->handleApiError($response);

        if ($response->successful()) {
            return redirect()->route('dashboard')->with('success', 'Propiedad eliminada correctamente.');
        } elseif ($response->status() === 403) {
            return redirect()->route('dashboard')->with('error', 'No tienes permiso para eliminar esta propiedad.');
        } elseif ($response->status() === 404) {
            return redirect()->route('dashboard')->with('error', 'Propiedad no encontrada.');
        }

        return redirect()->route('dashboard')->with('error', 'Error al eliminar la propiedad.');
    }

    public function buy(Request $request, $id)
    {
        $response = $this->apiClient()->post("{$this->apiUrl}/api/properties/{$id}/buy");

        $this->handleApiError($response);

        if ($response->successful()) {
            return redirect()->route('properties.index')->with('success', '¡Propiedad comprada exitosamente!');
        } elseif ($response->status() === 400 || $response->status() === 403) {
            $error = $response->json('error') ?? 'No se pudo comprar la propiedad.';
            return redirect()->route('properties.index')->with('error', $error);
        }

        return redirect()->route('properties.index')->with('error', 'Error inesperado al comprar la propiedad.');
    }

    public function purchase(Request $request, $id)
    {
        // Hacer la petición POST a la API para comprar la propiedad
        $response = $this->apiClient()->post("{$this->apiUrl}/api/properties/{$id}/buy");
    
        // Manejar errores
        if (!$response->successful()) {
            $status = $response->status();
            $error = $response->json('error') ?? 'Error inesperado al comprar la propiedad.';
    
            if (in_array($status, [400, 403, 404, 401])) {
                return redirect()->route('cart.index')->with('error', $error);
            }
    
            return redirect()->route('cart.index')->with('error', 'Error inesperado al comprar la propiedad.');
        }
    
        // Compra exitosa: redirigir con mensaje de éxito
        return redirect()->route('cart.index')->with('success', 'Propiedad comprada exitosamente y eliminada del carrito.');
    }
    
    
}
