<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap(); //to use bootstrap for our pagination,not the default tailwind

        Gate::define('admin',function($user){
            return $user->role_id===User::ADMIN_ROLE_ID;
        });

        #.env(ENvironment variables)
        if(config('app.env')==='production'){
            \URL::forceScheme('https'); //http,https
        }
    }
}