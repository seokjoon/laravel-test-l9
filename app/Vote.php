<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{

	protected $dates = ['voted_at'];

	protected $fillable = ['comment_id', 'down', 'up', 'user_id', 'voted_at'];

	public $timestamps = false;

	public function comment()
	{
		return $this->belongsTo(Comment::class);
	}

	public function setDownAttribute($value)
	{
		$this->attributes['down'] = $value ? 1 : null;
	}

	public function setUpAttribute($value)
	{
		$this->attributes['up'] = $value ? 1 : null;
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
