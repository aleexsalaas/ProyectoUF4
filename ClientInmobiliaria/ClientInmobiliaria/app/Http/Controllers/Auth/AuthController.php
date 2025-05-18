<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    // Mostrar formulario de login
    public function showLogin()
    {
        if (Session::has('api_token') && Session::has('user')) {
            return redirect()->route('dashboard');
        }
    
        return view('auth.login');
    }
    

    // Login: llamada a la API, guardado token y usuario en sesión
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Llamada a la API para login
        $response = Http::post(env('API_URL') . '/api/login', [
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if ($response->successful()) {
            $data = $response->json();

            // Guardar token y usuario en sesión para uso posterior
            Session::put('api_token', $data['token']);
            Session::put('user', $data['user']);

            return redirect()->route('properties.index');
        }

        // En caso de error devolver con mensaje
        return back()->withErrors([
            'login' => $response->json('message') ?? 'Credenciales incorrectas o error en la autenticación.'
        ])->withInput();
    }

    // Logout: limpiar sesión local y opcionalmente llamar API para invalidar tokens
    public function logout()
    {
        $token = Session::get('api_token');

        // Opcional: llamar a la API para cerrar sesión y eliminar token (no obligatorio)
        if ($token) {
            Http::withToken($token)->post(env('API_URL') . '/api/logout');
        }

        Session::forget('api_token');
        Session::forget('user');

        return redirect()->route('login')->with('message', 'Sesión cerrada correctamente');
    }

    // Ejemplo método protegido: llamar a API con token y mostrar datos
    public function dashboard()
    {
        $token = Session::get('api_token');

        if (!$token) {
            return redirect()->route('login')->withErrors(['login' => 'Debes iniciar sesión']);
        }

        $response = Http::withToken($token)->get(env('API_URL') . '/api/dashboard');

        if ($response->successful()) {
            $data = $response->json();

            return view('dashboard', ['data' => $data]);
        }

        // Token inválido o expirado: eliminar sesión y redirigir
        Session::forget('api_token');
        Session::forget('user');

        return redirect()->route('login')->withErrors(['login' => 'Token inválido, por favor inicia sesión de nuevo']);
    }
}
