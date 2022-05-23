<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //Relacion 1:N con prestamos
    public function prestamos() {
        return $this->hasMany(Prestamo::class,'id');
    }

    public function adminlte_image()
    {
        /* return 'https://picsum.photos/300/300'; */
        return 'https://lh3.googleusercontent.com/ogw/ADea4I7omsSrsBxku7KTRLL-mnVVqvvz_hf73JI5k-iKAA=s32-c-mo';
    }

    public function adminlte_desc()
    {
        return 'Administrador';
    }

    public function adminlte_profile_url()
    {
        return 'profile/username';
    }
}
