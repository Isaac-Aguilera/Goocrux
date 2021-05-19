<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    /**
     * Muestra el inicio de la aplicacion.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
}
