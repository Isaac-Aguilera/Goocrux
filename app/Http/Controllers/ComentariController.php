<?php

namespace App\Http\Controllers;

use App\Models\Comentari;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ComentariController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Crea un nuevo comentario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Recoje los datos passados por POST.
        $request['user_id'] = Auth::user()->id;
        $data = $request->all();

        // Valida el contenido del comentario.
        Validator::make($data, [
            'contingut' => ['string', 'max:500']
        ])->validate();

        // Crea el comentario y lo guarda en la base de datos.
        $comentari = new Comentari($data);
        $comentari->save();

        // Cuenta los comentarios que hay en el video.
        $comentaris = $comentari->video->comentaris->count();

        /**
         * Devuelve: 
         * 
         * - El numero de comentarios.
         * - El id del nuevo comentario.
         * - El nick del usuario que ha creado el usuario.
         * - El avatar del usuario que ha creado el comentario.
         */
        return array('comentaris' => $comentaris, 'id' => $comentari->id, 'nick' => $comentari->user->nick, 'image' => $comentari->user->image);
    }

    /**
     * Cambia el contenido de un comentario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // Obtiene el comentario.
        $id = $request->route('id');
        $comentari = Comentari::find($id);

        // Cambia el contenido y lo guarda.
        $comentari->contingut = $request['contingut'];
        $comentari->save();

        return true;
    }

    /**
     * Elimina un comentario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        // Obtiene el comentario.
        $id = $request->route('id');
        $comentari = Comentari::find($id);

        // Obtine el video del comentario antes de que se elimine el comentario.
        $video = $comentari->video;

        // Elimina el comentario.
        $comentari->delete();

        // Devuelve el numero de comentarios.
        $comentaris = $video->comentaris->count();
        return array('comentaris' => $comentaris);
    }
}
