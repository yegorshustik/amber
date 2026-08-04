<?php

use App\Http\Controllers\Api\Articles\ArticlesController;
use App\Http\Controllers\Api\Articles\RubricsController;
use App\Http\Controllers\Api\Articles\TagsController;
use App\Http\Controllers\Api\CatalogController;
use App\Http\Controllers\Api\ConfigurationController;
use App\Http\Controllers\Api\Filemanager\DirectoryController;
use App\Http\Controllers\Api\Filemanager\FileController;
use App\Http\Controllers\Api\Filemanager\TempController;
use App\Http\Controllers\Api\Inbox\ApplicationController;
use App\Http\Controllers\Api\Inbox\FieldsController;
use App\Http\Controllers\Api\Inbox\FormsController;
use App\Http\Controllers\Api\PagesController;
use App\Http\Controllers\Api\ReviewsController as SiteReviewsController;
use App\Http\Controllers\Api\ServicesController;
use App\Http\Controllers\Api\SignController;
use App\Http\Controllers\Api\SitesController;
use App\Http\Controllers\Api\UserController;
use App\Http\Middleware\SiteSelectorMiddleware;
use Illuminate\Support\Facades\Route;

Route::group([
    'as' => 'api.',
    'middleware' => SiteSelectorMiddleware::class,
], function () {
    /*
     * Sign
     */
    Route::group([
        'controller' => SignController::class,
    ], function () {
        Route::post('sign-in', 'signIn');
        Route::post('sign-out', 'signOut')->middleware('auth:sanctum');
    });

    /*
     * User
     */
    Route::group([
        'middleware' => 'auth:sanctum',
    ], function () {
        Route::get('user/check', [UserController::class, 'check']);
        Route::apiResource('user', UserController::class);
    });

    /*
     * Sites
     */
    Route::group([
        'middleware' => 'auth:sanctum',
    ], function () {
        Route::post('sites/sorting', [SitesController::class, 'sorting']);
        Route::delete('sites/mass-destroy', [SitesController::class, 'massDestroy']);
        Route::get('sites/list', [SitesController::class, 'list']);
        Route::apiResource('sites', SitesController::class);
    });

    /*
     * Services
     */
    Route::group([
        'middleware' => 'auth:sanctum',
    ], function () {
        Route::post('services/sorting', [ServicesController::class, 'sorting']);
        Route::delete('services/mass-destroy', [ServicesController::class, 'massDestroy']);
        Route::get('services/list', [ServicesController::class, 'list']);
        Route::apiResource('services', ServicesController::class);
    });

    /*
     * Catalog
     */
    Route::group([
        'middleware' => 'auth:sanctum',
    ], function () {
        Route::post('catalog/sorting', [CatalogController::class, 'sorting']);
        Route::delete('catalog/mass-destroy', [CatalogController::class, 'massDestroy']);
        Route::get('catalog/list', [CatalogController::class, 'list']);
        Route::apiResource('catalog', CatalogController::class);
    });

    /*
     * Configuration
     */
    Route::group([
        'middleware' => 'auth:sanctum',
        'controller' => ConfigurationController::class,
        'prefix' => 'configuration',
    ], function () {
        Route::get('', 'index');
        Route::post('store', 'store');
    });

    /*
     * Pages
     */
    Route::group([
        'middleware' => 'auth:sanctum',
        'controller' => PagesController::class,
        'prefix' => 'page',
    ], function () {
        Route::get('', 'index');
        Route::post('store', 'store');
        Route::get('{id}', 'show');
        Route::delete('{id}', 'destroy');
    });

    /*
     * Articles
     */
    Route::group([
        'middleware' => 'auth:sanctum',
        'prefix' => 'articles',
    ], function () {
        Route::post('rubrics/sorting', [RubricsController::class, 'sorting']);
        Route::delete('rubrics/mass-destroy', [RubricsController::class, 'massDestroy']);
        Route::get('rubrics/list', [RubricsController::class, 'list']);
        Route::apiResource('rubrics', RubricsController::class);

        Route::match(['get', 'post'], 'tags', TagsController::class);

        Route::delete('article/mass-destroy', [ArticlesController::class, 'massDestroy']);
        Route::get('article/list', [ArticlesController::class, 'list']);
        Route::apiResource('article', ArticlesController::class);
    });

    /*
     * Inbox
     */
    Route::group([
        'middleware' => 'auth:sanctum',
        'prefix' => 'inbox',
    ], function () {

        Route::delete('form/mass-destroy', [FormsController::class, 'massDestroy']);
        Route::get('form/list', [FormsController::class, 'list']);
        Route::post('form/sorting', [FormsController::class, 'sorting']);
        Route::apiResource('form', FormsController::class);

        Route::delete('field/mass-destroy', [FieldsController::class, 'massDestroy']);
        Route::get('field/list', [FieldsController::class, 'list']);
        Route::post('field/sorting', [FieldsController::class, 'sorting']);
        Route::apiResource('field', FieldsController::class);

        Route::delete('application/mass-destroy', [ApplicationController::class, 'massDestroy']);
        Route::post('application/export', [ApplicationController::class, 'export']);
        Route::apiResource('application', ApplicationController::class)->except(['update']);
    });

    /*
     * Filemanager
     */
    Route::group([
        'middleware' => 'auth:sanctum',
        'prefix' => 'filemanager',
    ], function () {

        /*
         * Directories
         */
        Route::group([
            'prefix' => 'directories',
        ], function () {
            Route::get('', [DirectoryController::class, 'index']);
            Route::post('store', [DirectoryController::class, 'store']);
            Route::post('update', [DirectoryController::class, 'update']);
            Route::post('remove', [DirectoryController::class, 'remove']);
        });

        /*
         * Files
         */
        Route::group([
            'prefix' => 'files',
        ], function () {
            Route::get('', [FileController::class, 'index']);
            Route::get('file', [FileController::class, 'file']);
            Route::get('search', [FileController::class, 'search']);
            Route::post('remove', [FileController::class, 'remove']);
            Route::post('upload', [FileController::class, 'upload']);
        });

        /*
         * Temporary Files
         */
        Route::group([
            'prefix' => 'temp',
        ], function () {
            Route::post('upload', [TempController::class, 'upload']);
        });
    });

    /*
     * Reviews
     */
    Route::group([
        'middleware' => 'auth:sanctum',
    ], function () {
        Route::post('reviews/sorting', [SiteReviewsController::class, 'sorting']);
        Route::delete('reviews/mass-destroy', [SiteReviewsController::class, 'massDestroy']);
        Route::get('reviews/list', [SiteReviewsController::class, 'list']);
        Route::apiResource('reviews', SiteReviewsController::class);
    });
});

// - ---------------------------------------------------------------------------------------------------------------------
