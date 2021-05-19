<?php

namespace App\Http\Controllers;

use App\Models\Vot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VotController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Crea un voto.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /**
         * Coje el id del usuario y del video por POST, seguidamente obtiene el voto.
         */
        $video_id = $request->all()['id'];
        $user_id = Auth::user()->id;
        $vot = Vot::all()->where('user_id', '=', $user_id)->where('video_id', '=', $video_id)->first();

        /**
         * Si no existe el voto crea uno nuevo, seguidamente pone el voto como like "1" o dislike "0".
         */
        if (!isset($vot)) {
            $vot = new Vot();
            $vot->user_id = $user_id;
            $vot->video_id = $video_id;
        }
        if ($request->all()['votacio'] == 'like') {
            $vot->votacio = 1;
        } else {
            $vot->votacio = 0;
        }
        $vot->save();

        /**
         * Devuelve el numero de likes y de dislikes del video.
         */
        return array(
            'likes' => $vot->video->vots->where('votacio', '=', 1)->count(),
            'dislikes' => $vot->video->vots->where('votacio', '=', 0)->count()
        );
    }

    /**
     * Elimina un voto.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function destroy(Request $request)
    {
        /**
         * Coje el id del usuario y del video por POST, seguidamente obtiene el voto y lo elimina.
         */
        $video_id = $request->all()['id'];
        $user_id = Auth::user()->id;
        $vot = Vot::all()->where('user_id', '=', $user_id)->where('video_id', '=', $video_id)->first();
        $vot->delete();

        /**
         * Devuelve el numero de likes y de dislikes del video.
         */
        return array(
            'likes' => Vot::all()->where('video_id', '=', $video_id)->where('votacio', '=', 1)->count(),
            'dislikes' => Vot::all()->where('video_id', '=', $video_id)->where('votacio', '=', 0)->count()
        );
    }
}
