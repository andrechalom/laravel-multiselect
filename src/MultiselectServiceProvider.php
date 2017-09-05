<?php

/*
 * This file is part of the Laravel Multiselect package.
 *
 * (c) Andre Chalom <andrechalom@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AndreChalom\LaravelMultiselect;

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
