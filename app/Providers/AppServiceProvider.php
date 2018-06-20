<?php

namespace App\Providers;

use Carbon\Carbon;
use DebugBar\DebugBar;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
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
    	//app()->setLocale('en');
    	//Carbon::setLocale(app()->getLocale());
		if($locale = request()->cookie('locale__l9')) {
			app()->setLocale(Crypt::decrypt($locale));
		}
		Carbon::setLocale(app()->getLocale());

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
    	$this->app->singleton('optimus', function() {
    		return new \Jenssegers\Optimus\Optimus(310461551, 325098127, 105645182);
		});
    }
}
