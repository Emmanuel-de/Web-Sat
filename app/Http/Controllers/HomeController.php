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
        return view('pages.home', compact('noticias', 'totalContribuyentes'));
    }

    public function dashboard() {
    $user = Auth::user();
    return view('pages.dashboard', compact('user'));
    }
}
