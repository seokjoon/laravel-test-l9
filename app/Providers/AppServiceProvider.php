<?php

namespace App\Providers;

use DebugBar\DebugBar;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    	view()->composer('*', function($view) {
    		$allTags = Cache::rememberForever('tags.list', function() {
    			return \App\Tag::all();
			});

    		$currentUser = auth()->user();
    		$currentRouteName = Route::currentRouteName();
    		$currentLocale = app()->getLocale();
    		$currentUrl = current_url();

    		$view->with(compact('allTags', 'currentLocale', 'currentRouteName', 'currentUrl', 'currentUser'));
		});

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    	/* if($this->app->environment('local')) {
    		$this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
		} */
    }
}
