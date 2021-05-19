<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Video;
use App\Models\Categoria;

class UserController extends Controller
{
    /**
     * Muestra los videos de el usuario.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $nick)
    {
        /**
         * Buscamos el usuario del cual hemos pasado el nick i si existe coje todas las categorias  y busca todos sus videos ordenados por data
         * de creación i vuelve la vista detall con el usuario, videos y categorias. En el caso de que no exista el usuario devuelve un error
         * de que el usuario no existe.
         */
        $user = User::where('nick', $nick)->first();
        if (isset($user)) {

            $categorias = Categoria::all();

            $posts = Video::query()->where("user_id", "=", "{$user->id}")->orderBy('created_at', 'DESC')->get();
            return view('user.detall')->with(['user' => $user, 'posts' => $posts, 'categorias' => $categorias]);
        } else {
            return view('user.detall')->with(['error' => "User not found!"]);
        }
    }

    /**
     * Muestra la vista de configuracion del usuario.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        /**
         * En el caso de que exista un usuario que ha hecho login devuelve el usuario a la vista config, en el caso de que no nos devolvera que
         * no existe el usuario.
         */
        if (isset(Auth::user()->id)) {
            return view('user.config')->with(['user' => Auth::user()]);
        } else {
            return view('user.config')->with(['error' => "User not found!"]);
        }
    }

    /**
     * Actualiza los datos del usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        /**
         * Recojemos todos los datos que hemos pasado por el form i lo ponemos en al variable $data, i cojemos el id del usuario logueado
         */

        $data = $request->all();
        $id = Auth::user()->id;

        Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'nick' => ['required', 'string', 'max:255', 'unique:users,nick,' . $id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $id],
            'file' => ['image'],
        ])->validate();

        /**
         * Buscamos el usuario con el id, hacemos que la variable $i sea la imagen actual del usuario i rellenamos todo el usuario con los datos
         * que le hemos pasado, en el caso de que exista un archivo, entonces lo guardamos en el store de avatars, borramos la imagen que tenia
         * el usuario i hacemos que la nueva imagen sea la que le hemos passado por el formulario. 
         *
         * En el caso de que no exista un nuevo archivo simplemente hacemos que la imagen del usuario sea la que ya tenia. Volvemos a la vista
         * config con un mensaje de usuario actualizado.
         */
        $user = User::find($id);
        $i = $user->image;
        $user->fill($data);
        if (isset($data['file'])) {
            $p = $data['file']->store('avatars');
            Storage::delete($user->image);
            $user->image = $p;
        } else {
            $user->image = $i;
        }
        $user->save();

        return redirect()->route('config')->with(['message' => 'User updated!']);
    }

    /**
     * Elimina un usuario y susu videos.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        /**
         * Eliminar Usuario
         * Buscamos el usuario i por cada video del usuario borramos en el storage los videos y miniaturas del video y borramos el video.
         *
         * Tambien borramos la imagen y banner del usuario y finalmente borramos el usuario.
         */
        $id = $request['id'];
        $user = User::find($id);
        foreach ($user->videos as $video) {
            Storage::delete($video->video_path);
            Storage::delete($video->image);
            $video->delete();
        }
        Storage::delete($user->image);
        Storage::delete($user->banner);
        $user->delete();
    }

    /**
     * Muestra la vista para cambiar la contraseña del usuario.
     *
     * @return \Illuminate\Http\Response
     */
    public function password()
    {
        /**
         * Devolvemos la vista de password del usuario si existe un usuario logueado.
         */
        if (isset(Auth::user()->id)) {
            return view('user.password');
        } else {
            return redirect('login');
        }
    }

    /**
     * Cambia la contraseña del usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request)
    {

        /**
         * En el caso de que no exista un usuario logueado devuelve a login
         *
         */
        if (!isset(Auth::user()->id)) {
            return redirect('login');
        }
        $data = $request->all();

        Validator::make($data, [
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ])->validate();

        /**
         * Buscamos la id y el usuario i de la password que hemos pasado por el formulario la encriptamos con Hash y rellenamos el usuario con
         * la nueva password y lo guardamos. Volvemos a la vista con un mensaje de password actualizada.
         */
        $id = Auth::user()->id;
        $user = User::find($id);
        $data['password'] = Hash::make($data['password']);
        $user->fill($data);
        $user->save();

        return redirect()->route('configPassword')->with(['message' => 'Password updated!']);
    }

    /**
     * Cambia la descripcion de un usuario..
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function canviardesc(Request $request)
    {
        Auth::user()->channel_desc = $request["desc"];
        Auth::user()->save();
    }

    /**
     * Cambia el banner de un usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function canviarbanner(Request $request)
    {
        $f = $request->file('img_logo');

        /**
         * En el caso de que no se haya pasado ninguna imagen nueva devuelve a la view con el usuario, videos y con un mensaje de error.
         */

        if ($f == null) {
            $nick = Auth::user()->nick;
            $posts = Video::query()->select('views')->where("user_id", "=", Auth::user()->id)->get();
            return redirect('/user/' . $nick . '/info')->with(['user' => Auth::user(), 'views' => $posts, 'incorrecte' => "You have to upload a file"]);
        }

        /**
         * Si existe un archivo, lo guardamos en el storage de banners, borramos el banner que ya tenia y lo guardamos en el usuario. Y hacemos
         * lo mismo que cuando no hay archivo pero esta vez con un mensaje de todo correcto.
         */
        $p = $f->store('banner');
        Storage::delete(Auth::user()->banner);
        Auth::user()->banner = $p;
        Auth::user()->save();

        $posts = Video::query()->select('views')->where("user_id", "=", Auth::user()->id)->get();
        $nick = Auth::user()->nick;
        return redirect('/user/' . $nick . '/info')->with(['user' => Auth::user(), 'views' => $posts, 'correcte' => "Done Correctly"]);
    }

    /**
     * Muestra la vista de los videos de un usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  String  $nick
     * @return \Illuminate\Http\Response
     */
    public function uservid(Request $request, $nick)
    {
        $user = User::where('nick', $nick)->first();
        if (isset($user)) {
            $posts = Video::query()->where("user_id", "=", "{$user->id}")->orderBy('created_at', 'DESC')->get();
            return view('user.videos')->with(['user' => $user, 'posts' => $posts]);
        } else {
            return view('user.videos')->with(['error' => "User not found!"]);
        }
    }

    /**
     * Muestra la vista de informacion de un usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  String  $nick
     * @return \Illuminate\Http\Response
     */
    public function userinfo(Request $request, $nick)
    {
        $user = User::where('nick', $nick)->first();
        if (isset($user)) {
            $posts = Video::query()->select('views')->where("user_id", "=", "{$user->id}")->get();
            return view('user.info')->with(['user' => $user, 'views' => $posts]);
        } else {
            return view('user.info')->with(['error' => "User not found!"]);
        }
    }

    /**
     * Muestra la vista del buscador de un usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  String  $nick
     * @return \Illuminate\Http\Response
     */
    public function usersearch(Request $request, $nick)
    {
        // Coje el valor de la busqueda
        $search = $request->input('search');

        /**
         * En el caso de que exista un usuario con el nick que hemos pasado, buscamos el usuario y cojemos la id, seguidamente buscaremos todos los
         * videos que tengan de id, el id que hemos cojido o un titulo parecido a la busqueda, ordenado por las visualizaciones.
         */
        if (User::where('nick', 'LIKE', "%{$nick}%")->first()) {
            $user = User::where('nick', '=', $nick)->first();

            $id = $user->id;


            $posts = Video::query()
                ->where('user_id', '=', $id)
                ->where('title', 'LIKE', "%{$search}%")
                ->orderBy('views', 'DESC')
                ->get();
        }
        /**
         * En el caso de que no exista el usuario simplemente buscara por titulo.
         */
        else {
            $posts = Video::query()
                ->where('title', 'LIKE', "%{$search}%")
                ->orderBy('views', 'DESC')
                ->get();
        }

        return view('user.search')->with(['posts' => $posts, 'user' => $user]);
    }

    /**
     * Muestra la vista manage de un usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  String  $nick
     * @return \Illuminate\Http\Response
     */
    public function uservidmanager(Request $request, $nick)
    {

        $user = User::where('nick', $nick)->first();
        if (!isset(Auth::user()->id)) {
            return redirect('login');
        } else if (isset($user)) {
            if (Auth::user()->id != $user->id) {
                return redirect('/user/' . $nick . '/videos');
            } else {
                $posts = Video::query()->where("user_id", "=", "{$user->id}")->orderBy('created_at', 'DESC')->get();
                return view('user.manage')->with(['user' => $user, 'posts' => $posts]);
            }
        } else {
            return view('user.videos')->with(['error' => "User not found!"]);
        }
    }

    /**
     * Muestra la vista de las recomendaciones de un usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  String  $nick
     * @return \Illuminate\Http\Response
     */
    public function userecommendations(Request $request, $nick)
    {
        $user = User::where('nick', $nick)->first();
        if (!isset(Auth::user()->id)) {
            return redirect('login');
        } else if (isset($user)) {
            if (Auth::user()->id != $user->id) {
                return redirect('/user/' . $nick . '/videos');
            } else {
                $posts = Video::query()->where("user_id", "=", "{$user->id}")->orderBy('created_at', 'DESC')->get();
                return view('user.recommendations')->with(['user' => $user, 'posts' => $posts]);
            }
        } else {
            return view('user.videos')->with(['error' => "User not found!"]);
        }
    }
}
