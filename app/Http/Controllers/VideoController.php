<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\User;
use App\Models\Valoracio;
use App\Jobs\UploadVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Categoria;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    /**
     * Muestra la vista dle video y suma una visualicacion.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /**
         * Busca el video con la id que hemos pasado por la url y en el caso de que exista incrementa las visualizaciones del video en 1,
         * y devuelve el video y valoraciones del video. En el caso de que no exista devuelve un error.
         */
        $video = Video::find($request->route('id'));
        if (isset($video)) {
            $video->increment('views', 1);
            return view('video.detall')->with(['video' => $video, 'valoracions' => Valoracio::all()->where('video_id', '=', $video->id)->groupBy('name')]);
        } else {
            return view('video.detall')->with(['error' => "Video not found!"]);
        }
    }

    /**
     * Devuelve la vista de crear un video con todas las categorias.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!isset(Auth::user()->id)) {
            return redirect('login');
        }
        return view('video.create')->with('categories', Categoria::orderBy('name')->get());
    }

    /**
     * Crea un nuevo video.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        Validator::make($data, [
            'video_path' => ['required', 'mimetypes:video/x-ms-asf,video/x-flv,video/mp4,application/x-mpegURL,video/MP2T,video/3gpp,video/quicktime,video/x-msvideo,video/x-ms-wmv,video/avi,video/webm'],
            'title' => ['required', 'string', 'min:5', 'max:255'],
            'description' => ['required', 'min:5'],
            'image' => ['required', 'image', 'dimensions:min_width=200,min_height=200'],
            'categoria_id' => ['required', 'integer'],
        ])->validate();


        /**
         * Cojemos el archivo de video que le hemos pasado i lo guardamos en el storage de videos y hacemos que el video_path de la data que hemos
         * pasado por el formulario sea lo que nos devuelve el storage y hacemos lo mismo con la imagen para la miniatura del video.
         *
         * En el caso de que la extension del video no sea webm hacemos un nuevo job para subir el video por atras del servidor y que el usuario
         * no tenga que esperarse i iniciamos el job que lo meteremos en una cola para que vaya haciendo automàticamente.
         */
        $f = $request->file('video_path');

        $p = $f->store('videos');

        $data['video_path'] = $p;
        $i = $data['image']->store('miniaturas');
        $data['image'] = $i;
        if ($f->extension() != "webm") {

            $job = new UploadVideo($data);
            $this->dispatch($job);
        }
        /**
         * En el caso de que ya sea .webm crea un nuevo video con la $data que tenemos i lo guardamos
         */
        else {
            $video = new Video($data);
            $video->save();
        }

        return redirect()->route('pujarVideo')->with(['message' => 'Video upload correctly']);
    }

    /**
     * Muestra la vista para editar un video.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        /**
         * En el caso de que no haya un usuario logueado devuelve al login.
         */
        if (!isset(Auth::user()->id)) {
            return redirect('login');
        }

        /**
         * Sinó busca el video por la id que hemos pasado por la ruta y en el caso de que exista devuelve la vista edit con todas las categorias,
         * sinó devuelve la vista con un error.
         */
        $video = Video::find($request->route('id'));
        if (isset($video)) {
            $video->increment('views', 1);
            return view('video.edit')->with('video', $video)->with('categories', Categoria::orderBy('name')->get());
        } else {
            return view('video.edit')->with(['error' => "Video not found!"]);
        }
    }

    /**
     * Cambia los datos del video.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $this->middleware('auth');
        $video = Video::find($request->route('id'));
        $data = $request->all();
        Validator::make($data, [
            'title' => ['required', 'string', 'min:5', 'max:255'],
            'description' => ['required', 'min:5'],
            'image' => ['image', 'dimensions:min_width=200,min_height=200'],
            'categoria_id' => ['required', 'integer'],
        ])->validate();

        /**
         * En el caso de que exista una nueva imagen de miniatura la guarda en el storage y borra la imagen antigua del storage y lo mete en 
         * la variable $data, usamos update para actualizar el video con los nuevos datos del formulario que hemos hecho.
         * Devuelve la vista con un mensaje de todo correcto.
         */
        if (isset($data['image'])) {
            $i = $data['image']->store('miniaturas');
            Storage::delete($video->image);
            $data['image'] = $i;
        }
        $video->update($data);
        return redirect()->route('editarVideo', $video->id)->with(['message' => 'Video edited correctly!']);
    }

    /**
     * Elimina el video.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request['id'];
        $video = Video::find($id);
        if (Auth::user()->id == $video->user_id) {

            /**
             * En el caso de que el usuario identificado sea el mismo que el creador del video, elimina del storage el video y la miniatura i elimina el video.
             */
            Storage::delete($video->video_path);
            Storage::delete($video->image);
            $video->delete();
        }
    }

    /**
     * Lista los videos de una busqueda.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        // Get the search value from the request
        $search = $request->input('search');

        /**
         * En el caso de que el nick del usuario sea parecido a la busqueda, cojeremos el usuario, el id i el nick. Cojeremos todos los videos 
         * que tengan un titulo parecido a la busqueda o el user_id del video sea parecido al id que hemos cojido antes, ordenado por visualizacion
         */
        if (User::where('nick', 'LIKE', "%{$search}%")->first()) {
            $user = User::where('nick', 'LIKE', "%{$search}%")->first();

            $id = $user->id;
            $username = User::where('nick', 'LIKE', '%' . $search . '%');
            $posts = Video::query()
                ->where('title', 'LIKE', "%{$search}%")
                ->orWhere('user_id', 'LIKE', "%{$id}%")
                ->orderBy('views', 'DESC')
                ->get();
        }
        /**
         * En el caso que no existe un usuario parecido a la busqueda solo buscara por titulo ordenado por visualizaciones.
         */
        else {
            $posts = Video::query()
                ->where('title', 'LIKE', "%{$search}%")
                ->orderBy('views', 'DESC')
                ->get();
        }

        // Return the search view with the resluts compacted
        return view('video.search')->with('videosearch', $posts);
    }
}
