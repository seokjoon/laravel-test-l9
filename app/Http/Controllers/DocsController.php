<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;


class DocsController extends Controller
{

	protected $docs;

	public function __construct(\App\Documentation $docs)
	{
		$this->docs = $docs;
	}

	public function image($file)
	{
		$reqEtag = Request::getEtags();
		$genEtag = $this->docs->etag($file);

		if(isset($reqEtag[0])) {
			if($reqEtag[0] === $genEtag) {
				return response('', 304);
			}
		}

		$image = $this->docs->image($file);
		//return response($image->encode('jpg'), 200, ['Content-Type' => 'image/jpg']);
		return response($image->encode('jpg'), 200, [
			'Content-Type' => 'image/jpg',
			'Cache-Control' => 'public, max-age=0',
			'Etag' => $genEtag,
		]);
	}

	public function show($file = null)
	{
		//$index =markdown($this->docs->get('documentation'));
		//$content = markdown($this->docs->get($file ?: 'installation.md'));
		$index = Cache::remember('docs.index', 120, function() {
			Log::debug('cache miss');
			return markdown($this->docs->get('documentation'));
		});
		$content = Cache::remember("docs.{$file}", 120, function() use($file) {
			Log::debug('cache miss');
			return markdown($this->docs->get($file ?: 'installation.md'));
		});

		return view('docs.show', compact('index', 'content'));
	}
}
