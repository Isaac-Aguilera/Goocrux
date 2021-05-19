<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property int $video_id
 * @property boolean $votacio
 * @property string $updated_at
 * @property string $created_at
 * @property Video $video
 * @property User $user
 */
class Vot extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'Vots';

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'video_id', 'votacio', 'updated_at', 'created_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function video()
    {
        return $this->belongsTo('App\Models\Video', 'video_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
