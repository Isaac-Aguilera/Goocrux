<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property int $video_id
 * @property string $noti_desc
 * @property boolean $state
 * @property string $type
 * @property User $user
 * @property Video $video
 */
class Notificacio extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'Notifications';

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'video_id', 'noti_desc', 'state'. 'type'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function video()
    {
        return $this->belongsTo('App\Models\Video', 'video_id');
    }
}
