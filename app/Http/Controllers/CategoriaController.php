<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Video;
use Illuminate\Support\Str;
use App\Helpers\FormatTime;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
     * Muestra todos los productos de la categoria selecionada ordenados por fecha de creacion.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Si el id de categoria es 0(No existe este id de categoria.) coje todos los productos, sino coje los productos de la categoria(Id de categoria.) passada por POST.
        if ($request['id'] == 0) {
            $videos = Video::orderBy('created_at', 'DESC')->get();
        } else {
            $videos = Video::orderBy('created_at', 'DESC')->where("categoria_id", "=", $request['id'])->get();
        }

        // Lista los productos cojidos para añadirlos a la vista.
        $a = "";
        foreach ($videos as $video) {
            $a = $a . '<div class="col-lg-4 col-md-6 col-sm-6">
                <div class="card mb-4 shadow">
                    <a href="' . route('video', $video->id) . '">
                        <video class="miniaturas w-100 p-0 m-0"  src="/' . $video->video_path . '" poster="/' . $video->image . '"  onmouseover="bigImg(this)" onmouseout="normalImg(this)" loop preload="none" muted="muted"></video>
                    </a>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-3">
                                <a href="' . route('user', $video->user->nick) . '">
                                    <img class="mr-1"
                                        style="border-radius:50%;width:2.5vw;min-width:40px;min-height:40px;"
                                        src="/' . $video->user->image . '">
                                </a>

                            </div>
                            <div class="col-9">
                                <strong><span title="' . $video->title . '">
                                        ' . Str::of($video->title)->limit(57, ' ...') . '
                                    </span></strong><br>


                                <span class="text-muted">' . $video->user->nick . '</span><br>
                                <span class="text-muted">' . $video->views . ' views</span><br>
                                <span class="text-muted">
                                    ' . FormatTime::LongTimeFilter($video->created_at) . '
                                </span>
                            </div>

                        </div>
                    </div>
                </div>
            </div>';
        }

        /**  
         * Devuelve:
         * 
         * - Si no ha listado nada(No hay productos de esta categoria) devuelve una advertencia de que no hay productos, sino devuelve el listado de productos.
         * - Nombre de la categoria .
         */
        if ($a == "") {
            return array("content" => "<div class='col-lg-12 col-md-12 col-sm-12'>
                <h4 class='alert alert-danger text-center'>There are no videos of " . Categoria::find($request['id'])->name . " category!</h4>
            </div>", 'name' => Categoria::find($request['id'])->name);
        } else {
            return array("content" => $a, 'name' => Categoria::find($request['id'])->name);
        }
    }
}
