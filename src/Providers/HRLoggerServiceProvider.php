


<?php

namespace HRLPackage\Providers;
use Illuminate\Support\ServiceProvider;
//  use HRLPackage\Services\YourService;
// use HRLPackage\Console\Commands\YourCommand;
// use HRLPackage\Events\YourEvent;
// use HRLPackage\Listeners\YourListener;


class HRLoggerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
       
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
       // Register routes
       $this->app['router']->group(['prefix' => 'hr-logger/v1'], function ($router) {
        $router->get('/index', [HrLoggerController::class, 'index']);
    });
    }
}