<?php

namespace App\Http\Controllers;

use App\Models\Notificacio;
use Illuminate\Support\Facades\Auth;

class NotificacioController extends Controller
{
    /**
     * Cuando le das a la campana de notificaciones esta funcion pone las notificaciones que havian en false así la proxima vez
     * no volveran a aparecer cuando hagas refresh.
     */
    public function netejarnoti()
    {
        $notificacions = Notificacio::all()->where('user_id', "=", Auth::user()->id);
        foreach ($notificacions as $noti) {
            $noti->state = false;
            $noti->save();
        }
    }
}
