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
            ->group(function () {
                require base_path('routes/panel.php');
            });
        }
    )
    ->withMiddleware(function(Middleware $middleware):void {
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function(Exceptions $exceptions):void {
//
    })->create();


