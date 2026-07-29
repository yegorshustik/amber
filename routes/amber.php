<?php

use App\Http\Controllers\Amber\ServicesController;
use App\Http\Controllers\Amber\InboxController;
use App\Http\Controllers\Amber\PageController;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

Route::group([
    'prefix' => locale_prefix(),
    'namespace' => 'App\Http\Controllers\Amber',
    'as' => 'amber.',
], function () {

    $router = function (): View {
        return (new PageController)->index();
    };

    Route::get('/', $router);
    Route::get('/services/{slug}', [ServicesController::class, 'show']);


    /*
     * Inbox
     */
    Route::group([
        'prefix' => locale_prefix(),
    ], function () {
        /*Route::get('/inbox/email/{id}', function($id){
            $application = \App\Models\Inbox\Application::with([
                'form', 'entities.field'
            ])->findOrFail($id);

            return new \App\Mail\Inbox($application);
        });*/
        Route::post('/inbox/{slug}', [InboxController::class, 'index'])->name('inbox');
    });

    Route::fallback($router);
});
