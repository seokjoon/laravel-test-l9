<?php

namespace App;

//use Illuminate\Database\Eloquent\Model;

//class Documentation extends Model
use Illuminate\Support\Facades\File;

class Documentation
{
	public function get($file)
	{
		$content = File::get($this->path($file));
		return $this->replaceLinks($content);
	}

	protected function path($file)
	{
		$file = ends_with($file, '.md') ? $file : $file . '.md';
		$path = base_path('docs' . DIRECTORY_SEPARATOR . $file);
		if(!(File::exists($path))) {
			abort(404, '요청하신 파일이 없습니다.');
		}
		return $path;
	}

	protected function replaceLinks($content)
	{
		return str_replace('/docs/{version}', '/docs', $content);
	}
}
