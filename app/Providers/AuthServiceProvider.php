<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
    	if((is_api_domain()) and (request()->getLanguages())) {
    		$preferred = request()->getPreferredLanguage();
    		$locale = str_contains($preferred, 'ko') ? 'ko' : 'en';
    		app()->setLocale($locale);
		}

        //$this->registerPolicies();

		/* Gate::before(function($user) {
			return $user->isAdmin();
		}); */

        Gate::define('update', function($user, $model) {
        	return $user->id === $model->user_id;
		});
        Gate::define('delete', function($user, $model) {
        	return $user->id === $model->user_id;
		});
    }
}
