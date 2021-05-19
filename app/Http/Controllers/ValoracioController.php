<?php

namespace App\Http\Controllers;

use App\Models\Valoracio;
use App\Models\Notificacio;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValoracioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Crea un nuevo voto de una valoracion, si se da el caso tambien crea una notificacion para el usuario del video.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /**
         * Coje los datos del form y obtiene la valoracion.
         */
        $video_id = $request->all()['video_id'];
        $user_id = Auth::user()->id;
        $name = $request->all()['name'];
        $valor = $request->all()['votacio'];

        $valoracio = Valoracio::all()->where('user_id', '=', $user_id)->where('video_id', '=', $video_id)->where('name', '=', $name)->first();

        /**
         * Si no existe crea la valoracion, sino cambia el valor de la valoracion.
         */
        if (!isset($valoracio)) {
            $valoracio = new Valoracio();
            $valoracio->user_id = $user_id;
            $valoracio->video_id = $video_id;
            $valoracio->name = $name;
            $valoracio->valoracio = $valor;
        } else {
            $valoracio->valoracio = $valor;
        }
        $valoracio->save();


        /**
         * Obtiene las valoracones separadas por el nombre.
         * Obtiene el video de la valoracion y coje el id del usuario del video.
         * Seguidamente esta la array que determina cuando se crea una notificacion.
         */
        $valoracions = Valoracio::all()->where('video_id', '=', $video_id)->groupBy('name');

        $video = Video::find($video_id);
        $user_vid_id = $video->user_id;

        $array_cont = array(2, 15, 50, 100, 250, 500, 1000, 5000, 10000);


        /**
         * Cuenta el valor de las valoraciones recojidas anteriormente y calcula la media de cada tipo de valoracion, 
         * las medias quedan recojidas en el array "mitjanes" en el cual en nombre es la key.
         */
        $mitjanes = array();
        foreach ($valoracions as $nom => $name) {
            $contador = 0;
            $suma = 0;
            foreach ($name as $id => $valoracio) {
                $contador = $contador + 1;
                $suma = $suma + $valoracio['valoracio'];
            }
            $mitjanes[$nom] = round($suma / $contador, 2);

            /**
             * Si el numero de valoraciones de un tipo es igual a alguno de los numeros en el arrray "array_cont" 
             * y la media esta por debajo o es igual a 2 crea una notificacion para el usuario del video, 
             * si el usuario ya tienen una notificacion de ese tipo y de ese video no se creara la notificacion.
             */
            if (in_array($contador, $array_cont)) {
                if ($mitjanes[$nom] <= 2) {
                    $desc = "The video " . $video->title . " has bad valorations on " . $nom . " quality";
                    if ($video->notificacions->where('state', "=", true)->where('type', "=", $nom)->first()) {
                    } else {
                        $notificacio = new Notificacio();
                        $notificacio->user_id = $user_vid_id;
                        $notificacio->video_id = $video_id;
                        $notificacio->noti_desc = $desc;
                        $notificacio->state = true;
                        $notificacio->type = $nom;
                        $notificacio->save();
                    }
                }
            }
        }

        /**
         * Devuelve la media de las valoraciones.
         */
        return array(
            'mitjanes' => $mitjanes

        );
    }

    /**
     * Elimina una valoracion.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        /**
         * Coje los datos del form y obtiene la valoracion, seguidamente la elimina.
         */
        $video_id = $request->all()['video_id'];
        $user_id = Auth::user()->id;
        $name = $request->all()['name'];
        $valoracio = Valoracio::all()->where('user_id', '=', $user_id)->where('video_id', '=', $video_id)->where('name', '=', $name)->first();
        $valoracio->delete();

        /**
         * Cuenta el valor de las valoraciones recojidas anteriormente y calcula la media de cada tipo de valoracion, 
         * las medias quedan recojidas en el array "mitjanes" en el cual en nombre es la key.
         */
        $mitjanes = array();
        $valoracions = Valoracio::all()->where('video_id', '=', $video_id)->groupBy('name');
        foreach ($valoracions as $nom => $name) {
            $contador = 0;
            $suma = 0;
            foreach ($name as $id => $valoracio) {
                $contador = $contador + 1;
                $suma = $suma + $valoracio['valoracio'];
            }
            $mitjanes[$nom] = round($suma / $contador, 2);
        }

        /**
         * Devuelve la media de las valoraciones.
         */
        return array(
            'mitjanes' => $mitjanes

        );
    }
}
