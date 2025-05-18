<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Property;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'error' => 'No autenticado',
                    'message' => 'Debes iniciar sesión para acceder al dashboard'
                ], 401);
            }

            $user = Auth::user();

            $userProperties = Property::where('user_id', $user->id)->get();
            $purchasedProperties = Property::where('buyer_id', $user->id)->get();

            return response()->json([
                'user' => $user,
                'user_properties' => $userProperties,
                'purchased_properties' => $purchasedProperties,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener el dashboard',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
