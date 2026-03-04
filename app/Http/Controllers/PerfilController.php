<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PerfilController extends Controller
{
    public function index()
{
    // Usamos el punto para entrar en la carpeta 'pages'
    return view('pages.Perfil'); 
}

public function actualizar(Request $request)
{
    $user = auth()->user();

    $request->validate([
        'nombres'          => ['required', 'string', 'max:80'],
        'primer_apellido'  => ['required', 'string', 'max:50'],
        'segundo_apellido' => ['nullable', 'string', 'max:50'],
        'fecha_nacimiento' => ['required', 'date'],
    ]);

    // Actualizamos solo los campos permitidos
    $user->update($request->only([
        'nombres', 
        'primer_apellido', 
        'segundo_apellido', 
        'fecha_nacimiento'
    ]));

    return back()->with('success', '¡Perfil actualizado con éxito!');
}

}
