<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
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

    public function index()
    {
        $response = $this->apiClient()->get("{$this->apiUrl}/api/dashboard");

        $this->handleApiError($response);

        if ($response->successful()) {
            $data = $response->json();

            return view('dashboard', [
                'user' => session('user'), // o $data['user'] si te llega
                'userProperties' => $data['user_properties'] ?? [],
                'boughtProperties' => $data['purchased_properties'] ?? [],
            ]);
        }

        return redirect()->route('dashboard')->with('error', 'No se pudo cargar el dashboard.');
    }
}
