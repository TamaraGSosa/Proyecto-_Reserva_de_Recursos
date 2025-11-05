<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Routing\Router;



    return Application::configure(basePath:dirname(__DIR__))
    ->withRouting(
        function(Router $router){
            $router ->middleware('web')
            ->group (base_path('routes/web.php'));
            $router -> middleware(['web' ,'auth'])
            ->prefix('panel')
            ->group(base_path('routes/panel.php'));
        }
    )
    ->withMiddleware(function(Middleware $middleware):void {
        //
    })
    ->withExceptions(function(Exceptions $exceptions):void {
//
    })->create();


