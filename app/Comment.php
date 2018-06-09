<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

	protected $appends = ['down_count', 'up_count'];

	protected $fillable = ['commentable_id', 'commentable_type', 'content', 'parent_id', 'user_id'];

	protected $with = ['user', 'votes'];

	public function commentable()
	{
		return $this->morphTo();
	}

	public function getDownCountAttribute()
	{
		return (int) $this->votes()->sum('down');
	}

	public function getUpCountAttribute()
	{
		return (int) $this->votes()->sum('up');
	}

	public function parent()
	{
		return $this->belongsTo(Comment::class, 'parent_id', 'id');
	}

	public function replies()
	{
		return $this->hasMany(Comment::class, 'parent_id')->latest();
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function votes()
	{
		return $this->hasMany(Vote::class);
	}
}