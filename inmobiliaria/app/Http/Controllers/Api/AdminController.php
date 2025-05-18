<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Resource;  // Asegúrate que este modelo existe

class AdminController extends Controller
{
    // Listar todos los recursos (o los que el admin debe gestionar)
    public function index()
    {
        $resources = Resource::all();

        return response()->json([
            'resources' => $resources,
        ]);
    }

    // Aquí puedes agregar más métodos para gestionar recursos, usuarios, etc.
}
