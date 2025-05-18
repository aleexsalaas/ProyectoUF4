<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class ReviewController extends Controller
{
    // Mostrar las reseñas de una propiedad (público, no requiere token)
    public function index($propertyId)
    {
        $response = Http::get(env('API_URL') . "/api/properties/{$propertyId}/reviews");
    
        if ($response->successful()) {
            $reviews = $response->json();
    
            // Guardar en sesión temporal para que show los use, o mejor aún, redirigir con datos
            return redirect()->route('properties.show', ['id' => $propertyId])
            ->with('reviews', $reviews);
        }
    
        abort($response->status(), 'Error al obtener las reseñas.');
    }
    

    // Crear nueva reseña para una propiedad (requiere token y sesión)
    public function store(Request $request, $propertyId)
    {
        $request->validate([
            'comment' => 'required|string|max:500',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $token = Session::get('api_token');

        if (!$token) {
            return redirect()->route('login')->withErrors(['auth' => 'Debes iniciar sesión para dejar una reseña.']);
        }

        $response = Http::withToken($token)->post(env('API_URL') . "/api/properties/{$propertyId}/reviews", [
            'comment' => $request->comment,
            'rating' => $request->rating,
        ]);

        if ($response->successful()) {
            $data = $response->json();

            return redirect()->route('reviews.index', ['propertyId' => $propertyId])
                             ->with('success', $data['message'])
                             ->with('newReview', $data['review']);
        }

        return back()->withErrors(['error' => $response->json()['message'] ?? 'Error desconocido']);
    }
}
