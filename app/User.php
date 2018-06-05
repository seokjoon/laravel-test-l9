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

	public function getGravatarUrlAttribute() //eloquent model accessor test
	{
		return sprintf('//www.gravatar.com/avatar/%s?s=%s', md5($this->email), 48);
	}

	public function isAdmin()
	{
		return false; //TODO
	}

	public function scopeSocialUser($query, $email)
	{
		return $query->whereEmail($email)->whereNull('password');
	}
}
