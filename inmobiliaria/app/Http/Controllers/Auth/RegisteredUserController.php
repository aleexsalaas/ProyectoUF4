<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8'], // Puedes usar Rules\Password si quieres
        ]);
    
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
    
            event(new Registered($user)); // Si quieres disparar el evento de registro
    
            $token = $user->createToken('YourAppName')->plainTextToken;
    
            return response()->json([
                'user' => $user,
                'token' => $token,
            ], 201);
        } catch (\Exception $e) {
            // Puedes también registrar el error en logs si quieres
            \Log::error('Error en registro de usuario: ' . $e->getMessage());
    
            return response()->json([
                'error' => 'Error al registrar el usuario.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    
}
