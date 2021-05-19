<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Video;
use FFMpeg;
use Illuminate\Support\Facades\Storage;

class UploadVideo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Crea una instancia Job.
     *
     * @return void
     */

    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Ejecuta la instancia Job.
     *
     * @return void
     */
    public function handle()
    {
        /**
         * Coje el video y crea una copia con formato .webm, seguidamente elimnina el video original y guarda la copia.
         */
        $p = $this->data['video_path'];
        FFMpeg::open($p)->export()->inFormat(new FFMpeg\Format\Video\WebM)->save(preg_replace('/\\.[^.\\s]{3,4}$/', '', $p) . '.webm');
        Storage::delete($p);
        $this->data['video_path'] = preg_replace('/\\.[^.\\s]{3,4}$/', '', $p) . '.webm';
        $video = new Video($this->data);
        $video->save();
    }
}
