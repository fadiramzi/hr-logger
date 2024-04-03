<?php
namespace Fadiramzi99\HrLogger\Providers;
use Illuminate\Support\ServiceProvider;

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