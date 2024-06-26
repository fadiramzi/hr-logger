<?php
namespace Fadiramzi99\HrLogger\Providers;
use Illuminate\Support\ServiceProvider;
use Fadiramzi99\HrLogger\Controllers\MainController;

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
            $router->get('/index', [MainController::class, 'index']);
        });

        // Auto-publish migrations
        $this->publishes([
            __DIR__.'/../Database/migrations' => database_path('migrations')
        ], 'hr-logger-migrations');

        // Auto-publish config
        $this->publishes([
            __DIR__.'/../Config/hr-logger.php' => config_path('hr-logger.php')
        ], 'hr-logger-config');

        
        // Auto-publish middleware
        $this->publishes([
            __DIR__.'/../Http/Middleware' => app_path('Http/Middleware')
        ], 'hr-logger-middleware');
    }
}