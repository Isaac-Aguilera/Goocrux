<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property int $categoria_id
 * @property string $title
 * @property string $description
 * @property string $image
 * @property string $video_path
 * @property int $views
 * @property string $updated_at
 * @property string $created_at
 * @property Category $category
 * @property User $user
 * @property Comentari[] $comentaris
 * @property Valoracio[] $valoracions
 * @property Vot[] $vots
 */
class Video extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'Videos';

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'categoria_id', 'title', 'description', 'image', 'video_path', 'views', 'updated_at', 'created_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function categoria()
    {
        return $this->belongsTo('App\Models\Categoria', 'categoria_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comentaris()
    {
        return $this->hasMany('App\Models\Comentari', 'video_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function valoracions()
    {
        return $this->hasMany('App\Models\Valoracio', 'video_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vots()
    {
        return $this->hasMany('App\Models\Vot', 'video_id');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notificacions()
    {
        return $this->hasMany('App\Models\Notificacio', 'video_id');
    }
}
