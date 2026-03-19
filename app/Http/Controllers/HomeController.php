<?php

namespace App\Http\Controllers;

use App\Models\Noticia;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $noticias = Noticia::activas()->recientes(3)->get();
        $totalContribuyentes = User::count();
        $totalFacturas = \DB::table('facturas')->count();
        return view('pages.home', compact('noticias', 'totalContribuyentes', 'totalFacturas'));
    }

    public function dashboard() {
    $user = Auth::user();
    return view('pages.dashboard', compact('user'));
    }
}
