<?php

namespace App\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property int $id
 * @property string $name
 * @property string $surname
 * @property string $nick
 * @property string $email
 * @property string $password
 * @property string $image
 * @property string $role
 * @property string $updated_at
 * @property string $created_at
 * @property string channel_desc
 * @property string banner
 * @property Comentari[] $comentaris
 * @property Valoracion[] $valoracions
 * @property Video[] $videos
 * @property Vot[] $vots
 * 
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'users';

    /**
     * @var array
     */
    protected $fillable = ['name', 'surname', 'nick', 'email', 'password', 'image', 'role','Vots','channel_desc','banner'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comentaris()
    {
        return $this->hasMany('App\Models\Comentari', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function valoracions()
    {
        return $this->hasMany('App\Models\Valoracio', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function videos()
    {
        return $this->hasMany('App\Models\Video', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vots()
    {
        return $this->hasMany('App\Models\Vot', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notificacions()
    {
        return $this->hasMany('App\Models\Notificacio', 'user_id');
    }
}
