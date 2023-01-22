<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('dev', function ($user) {
            return $user->role == '0';
        });
        Gate::define('ga', function ($user) {
            return $user->role == '1';
        });
        Gate::define('gastaff', function ($user) {
            return $user->role == '2';
        });
        Gate::define('fo', function ($user) {
            return $user->role == '3';
        });
        Gate::define('dept', function ($user) {
            return $user->role == '4';
        });
        Gate::define('driver', function ($user) {
            return $user->role == '5';
        });
        Gate::define('general', function ($user) {
            return $user->role == '6';
        });
    }
}
