<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Property;

class ReviewController extends Controller
{
    // Asumimos que la autenticación se gestiona en las rutas API con middleware

    // Crear nueva reseña para una propiedad
    public function store(Request $request, $propertyId)
    {
        try {
            $request->validate([
                'comment' => 'required|string|max:500',
                'rating' => 'required|integer|min:1|max:5',
            ]);
    
            $property = Property::findOrFail($propertyId);
    
            $review = Review::create([
                'user_id' => auth()->id(),
                'property_id' => $property->id,
                'comment' => $request->comment,
                'rating' => $request->rating,
            ]);
    
            return response()->json([
                'message' => 'Comentario agregado correctamente.',
                'review' => [
                    'user_name' => auth()->user()->name,
                    'comment' => $review->comment,
                    'rating' => $review->rating,
                    'created_at' => $review->created_at->diffForHumans(),
                ],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Server Error',
                'message' => $e->getMessage(),
                'trace' => $e->getTrace(),
            ], 500);
        }
    }
    

    // Listar reviews de una propiedad
    public function index($propertyId)
    {
        try {
            $property = Property::findOrFail($propertyId);
    
            $reviews = $property->reviews()->with('user')->get();
    
            return response()->json($reviews, 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Property not found',
                'message' => $e->getMessage(),
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Server Error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    
}
