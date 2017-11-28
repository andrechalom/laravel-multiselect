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

/**
 * @codeCoverageIgnore
 */
class MultiselectServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Register the package services.
     */
    public function register()
    {
        $this->app->singleton('multiselect', function ($app) {
            return new Multiselect($app['session.store'], $app['request']);
        });
    }

    public function provides()
    {
        return ['multiselect'];
    }
}
