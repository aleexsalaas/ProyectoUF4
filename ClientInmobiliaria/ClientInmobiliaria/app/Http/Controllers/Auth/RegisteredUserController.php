<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validar localmente antes de enviar la petición a la API
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);
    
        // Llamada a la API externa para registrar usuario
        $response = Http::post(env('API_URL') . '/register', [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'password_confirmation' => $request->password_confirmation,
        ]);
    
        if ($response->successful()) {
            $data = $response->json();
    
            // Guardar token en sesión o donde corresponda
            session(['api_token' => $data['token']]);
            // También podrías autenticar localmente o hacer lo que necesites
    
            return redirect()->intended(RouteServiceProvider::HOME);
        }
    
        // Si hubo error, redirigir con mensaje de error
        return back()->withErrors(['api_error' => $response->json('message') ?? 'Error al registrar usuario'])->withInput();
    }
}
