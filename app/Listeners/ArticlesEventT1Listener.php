<?php

namespace App\Listeners;

use App\Events\AppEventsArticleEventT1;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ArticlesEventT1Listener
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
     * @param  AppEventsArticleEventT1  $event
     * @return void
     */
    public function handle(\App\Events\ArticleCreatedT1 $event)
    {
		dump('event catch');
		dump($event->article->toArray());
    }
}
