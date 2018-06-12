<?php

namespace App\Http\Controllers;

use function foo\func;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $cache;

    public function __construct()
	{
		$this->cache = app('cache');
		if((new \ReflectionClass($this))->implementsInterface(Cacheable::class) and taggable()) {
			$this->cache = app('cache')->tags($this->cacheTags());
		}
	}

	protected function cache($key, $minutes, $query, $method, ...$args)
	{
		$args = (!(empty($args)) ? implode(',', $args) : null);
		if(config('project.cache') === false) {
			Log::debug('false');
			return $query->{$method}($args);
		}
		/* return Cache::remember($key, $minutes, function() use($query, $method, $args) {
			Log::debug('true');
			return $query->{$method}($args);
		}); */
		return $this->cache->remember($key, $minutes, function() use($query, $method, $args) {
			Log::debug('true');
			return $query->{$method}($args);
		});
	}

}
