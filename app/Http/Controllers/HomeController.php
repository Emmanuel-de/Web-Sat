<?php

namespace App\Http\Controllers;

use App\Models\Noticia;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $noticias = Noticia::activas()->recientes(3)->get();

        return view('pages.home', compact('noticias'));
    }

    public function dashboard() {
    $user = Auth::user();
    return view('pages.dashboard', compact('user'));
    }
}
