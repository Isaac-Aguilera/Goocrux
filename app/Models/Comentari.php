<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property int $video_id
 * @property string $contingut
 * @property string $updated_at
 * @property string $created_at
 * @property User $user
 * @property Video $video
 */
class Comentari extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'Comentaris';

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'video_id', 'contingut', 'updated_at', 'created_at'];

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
