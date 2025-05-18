<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resource;  // Asegúrate de que el modelo Resource exista

class AdminController extends Controller
{
    public function index()
    {
        // Aquí puedes obtener todos los recursos o los que el admin debería gestionar
        $resources = Resource::all();  // Trae todos los recursos

        return view('admin.resources.index', compact('resources'));  // Pasamos los recursos a la vista
    }

    // Puedes agregar más métodos para gestionar recursos, usuarios, etc.
}
