<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // $exceptions->render(function(\Exception $e){
        //     if(request()->expectsJson()){
        //         dd($e);
        //         return apiErrorResponse($e->getMessage(),401);
        //     }
        // });
        $exceptions->render(function(ValidationException $e){
            if(request()->expectsJson()){
                return apiErrorResponse($e->getMessage(),422, $e->errors());
            }
        });
    })->create();
