<?php

namespace Yab\LaraTest;

use Illuminate\Support\ServiceProvider;

class LaraTestProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        /*
        |--------------------------------------------------------------------------
        | Register the Commands
        |--------------------------------------------------------------------------
        */

        $this->commands([
            \Yab\LaraTest\Console\Route::class,
            \Yab\LaraTest\Console\Unit::class,
        ]);
    }
}
