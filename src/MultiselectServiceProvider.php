<?php

namespace andrechalom\laravel_multiselect;

use Illuminate\Support\ServiceProvider;

class MultiselectServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('multiselect', function ($app) {
            return new Multiselect($app);
        });
    }
}
