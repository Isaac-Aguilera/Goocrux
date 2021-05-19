<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property int $video_id
 * @property string $name
 * @property int $valoracio
 * @property User $user
 * @property Video $video
 */
class Valoracio extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'Valoracions';

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'video_id', 'name', 'valoracio'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function video()
    {
        return $this->belongsTo('App\Models\Video', 'video_id');
    }
}
