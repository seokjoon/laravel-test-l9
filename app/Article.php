<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{

	protected $fillable = ['title', 'content'];

	//protected $with = ['user'];

	public function attachments()
	{
		return $this->hasMany(Attachment::class);
	}

	public function comments()
	{
		return $this->morphMany(Comment::class, 'commentable');
	}

	public function tags()
	{
		return $this->belongsToMany(Tag::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
