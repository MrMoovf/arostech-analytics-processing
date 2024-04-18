<?php

namespace Arostech\Analytics;

use Arostech\Middleware\LogRequests;
use Arostech\Console\Commands\ProcessAnalytics;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Http\Kernel;


class AnalyticsServiceProvider extends ServiceProvider {

    public function boot (Kernel $kernel): void {
        $kernel->appendMiddlewareToGroup('web', LogRequests::class); // Add it after all other middlewares

        $this->commands([
            ProcessAnalytics::class,
        ]);

        // include route to register new traffic on the front end
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');

    
    }

    public function register (): void {

        

    }
}