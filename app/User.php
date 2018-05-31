<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $casts = ['activated' => 'boolean'];

    protected $dates = ['last_login'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'activated',
    	'confirm_code',
		'email',
		'name',
		'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'confirm_code', 'password', 'remember_token',
    ];

    public function articles()
	{
		return $this->hasMany(Article::class);
	}
}
