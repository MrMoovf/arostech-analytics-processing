<?php

namespace Arostech\Analytics;

use Illuminate\Support\ServiceProvider;
use Arostech\Analytics\LogRequests;
use Illuminate\Contracts\Http\Kernel;


class AnalyticsServiceProvider extends ServiceProvider {

    public function boot (Kernel $kernel): void {
        $kernel->appendMiddlewareToGroup('web', LogRequests::class); // Add it after all other middlewares
    }

    public function register (): void {

        

    }
}