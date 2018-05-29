<?php

namespace App;

//use Illuminate\Database\Eloquent\Model;

//class Documentation extends Model
use Illuminate\Support\Facades\File;

class Documentation
{

	public function etag($file)
	{
		$modified = File::lastModified($this->path($file, 'docs/images'));
		return md5($file . $modified);
	}

	public function get($file)
	{
		$content = File::get($this->path($file));
		return $this->replaceLinks($content);
	}

	public function image($file)
	{
		//return \Intervention\Image\Image::make($this->path($file, 'docs/images'));
		return \Image::make($this->path($file, 'docs/images'));
	}

	protected function path($file, $dir = 'docs')
	{
		//$file = ends_with($file, '.md') ? $file : $file . '.md';
		$file = ends_with($file, ['.md', '.jpg']) ? $file : $file . '.md';
		$path = base_path($dir . DIRECTORY_SEPARATOR . $file);
		if(!(File::exists($path))) {
			abort(404, '요청하신 파일이 없습니다.');
		}
		return $path;
	}

	protected function replaceLinks($content)
	{
		return str_replace('/docs/{{version}}', '/docs', $content);
	}
}
