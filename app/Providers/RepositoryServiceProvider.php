<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(\App\Repositories\GroupRepository::class, \App\Repositories\GroupRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\ContactRepository::class, \App\Repositories\ContactRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\ShortMessageRepository::class, \App\Repositories\ShortMessageRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\PendingRepository::class, \App\Repositories\PendingRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\BlacklistedNumberRepository::class, \App\Repositories\BlacklistedNumberRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\WhitelistedNumberRepository::class, \App\Repositories\WhitelistedNumberRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\InfoRepository::class, \App\Repositories\InfoRepositoryEloquent::class);
        //:end-bindings:
    }
}
