<?php
namespace Noorisyslaravel\Zoom\Providers;

use App\Providers\RouteServiceProvider;

class LaravelZoomProvider extends RouteServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        
        $this->app['router']->prefix('noorisys')->group(function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        });
    }
}
