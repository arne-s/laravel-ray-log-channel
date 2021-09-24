<?php

namespace AFSDev\RayLogChannel;

use Illuminate\Log\LogManager;
use Illuminate\Support\ServiceProvider;

class RayLogChannelServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app['log'] instanceof LogManager) {
            $this->app['log']->extend('ray', function ($app, array $config) {
                $logger = new RayLogger();

                return $logger($config);
            });
        }
    }
}
