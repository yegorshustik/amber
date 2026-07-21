<?php

use App\Http\Controllers\Amber\PageController;
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Illuminate\Http\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {

            if (! file_exists(base_path('routes/'.site()->slug.'.php'))) {
                abort(Response::HTTP_NOT_FOUND);
            }

            Route::middleware('web')
                ->domain(site()->domain_alternative == request()->getHost() ? site()->domain_alternative : site()->domain)
                ->group(base_path('routes/'.site()->slug.'.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);
        $middleware->trustProxies(at: '*');

        $middleware->preventRequestForgery(except: [
            'checkout/payment/*/response',
        ]);
        $middleware->preventRequestsDuringMaintenance(except: [
            'checkout/payment/*/response',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->respond(function (Response|JsonResponse $response) {
            if ($response->getStatusCode() === Response::HTTP_NOT_FOUND) {
                (new PageController)->notFound($response);
            }

            return $response;
        });
    })->create();
