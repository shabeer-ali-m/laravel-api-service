<?php

namespace Smartphp\LaravelApiService;

use Illuminate\Support\ServiceProvider;

class LaravelApiServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->commands(MakeService::class);
    }
}
