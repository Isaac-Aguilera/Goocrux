<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property Video[] $videos
 */
class Categoria extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'Categories';

    /**
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function videos()
    {
        return $this->hasMany('App\Models\Video', 'categoria_id');
    }
}
