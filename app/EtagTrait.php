<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

trait EtagTrait
{
	public function etag(Model $model, $cacheKey = null)
	{
		$etag = '';
		if($model->usesTimestamps()) {
			$etag .= $model->updated_at->timestamp;
		}
		return md5($etag . $cacheKey);
	}

	protected function etags($collection, $cacheKey = null)
	{
		$etag = '';
		foreach ($collection as $instance) {
			$etag .= $this->etag($instance);
		}
		return md5($etag . $cacheKey);
	}
}