<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        /* Event::listen('article.created', function($article) {
			dump('event catch');
			dump($article->toArray());
		}); */
        //Event::listen('article.created', \App\Listeners\ArticlesEventListener::class);
        Event::listen(
        	\App\Events\ArticleCreated::class,
			\App\Listeners\ArticlesEventListener::class
		);
    }
}
