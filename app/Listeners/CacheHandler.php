<?php

namespace App\Listeners;

use App\Events\ModelChanged;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;

class CacheHandler
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ModelChanged  $event
     * @return void
     */
    public function handle(ModelChanged $event)
    {
    	//Cache::flush();
		if(!(taggable())) return Cache::flush();
		return Cache::tags($event->cacheTags)->flush();
    }
}
