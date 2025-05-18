<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;  // <--- Falta esto
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;

class SessionController extends Controller
{
    public function index()
    {
        $token = Session::get('api_token');

        $response = Http::withToken($token)->get(env('API_URL') . '/api/properties');

        if ($response->successful()) {
            $properties = $response->json();
            return view('properties.index', compact('properties'));
        } else {
            return abort(401, 'No autorizado o error en la API');
        }
    }

    public function saveToken(Request $request)
    {
        $token = $request->input('token');
        // Guardarlo en sesión o base de datos según necesites
        Session::put('api_token', $token);

        return response()->json(['message' => 'Token guardado correctamente']);
    }
}
