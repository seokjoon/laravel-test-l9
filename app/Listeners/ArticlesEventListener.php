<?php

namespace App\Listeners;

//use App\Events\article.created;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ArticlesEventListener
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
     * @param  article.created  $event
     * @return void
     */
    //public function handle(article.created $event)
	//public function handle(\App\Article $article)
	public function handle(\App\Events\ArticleCreated $event)
    {
		dump('event catch');
		//dump($article->toArray());
		dump($event->article->toArray());
    }
}
