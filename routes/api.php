<?php

use App\Http\Controllers\Api\Articles\ArticlesController;
use App\Http\Controllers\Api\Articles\RubricsController;
use App\Http\Controllers\Api\Articles\TagsController;
use App\Http\Controllers\Api\BannersController;
use App\Http\Controllers\Api\Catalog\BrandsController;
use App\Http\Controllers\Api\Catalog\CategoriesController;
use App\Http\Controllers\Api\Catalog\ManufacturersController;
use App\Http\Controllers\Api\Catalog\OptionsController;
use App\Http\Controllers\Api\Catalog\OptionValuesController;
use App\Http\Controllers\Api\Catalog\ProductsController;
use App\Http\Controllers\Api\Catalog\ReviewsController;
use App\Http\Controllers\Api\Catalog\SavedFiltersController;
use App\Http\Controllers\Api\Catalog\StatusesController;
use App\Http\Controllers\Api\Catalog\StockController;
use App\Http\Controllers\Api\CatalogController;
use App\Http\Controllers\Api\ConfigurationController;
use App\Http\Controllers\Api\Equipment\CategoriesController as EquipmentCategoriesController;
use App\Http\Controllers\Api\Equipment\ModelsController as EquipmentModelsController;
use App\Http\Controllers\Api\Equipment\SchemesController as EquipmentSchemesController;
use App\Http\Controllers\Api\Equipment\SerialsController as EquipmentSerialsController;
use App\Http\Controllers\Api\Filemanager\DirectoryController;
use App\Http\Controllers\Api\Filemanager\FileController;
use App\Http\Controllers\Api\Filemanager\TempController;
use App\Http\Controllers\Api\Inbox\ApplicationController;
use App\Http\Controllers\Api\Inbox\FieldsController;
use App\Http\Controllers\Api\Inbox\FormsController;
use App\Http\Controllers\Api\Orders\OrdersController;
use App\Http\Controllers\Api\Orders\StatusesController as OrderStatusesController;
use App\Http\Controllers\Api\Orders\TrashController;
use App\Http\Controllers\Api\PagesController;
use App\Http\Controllers\Api\ReviewsController as SiteReviewsController;
use App\Http\Controllers\Api\ServicesController;
use App\Http\Controllers\Api\Shipping\AddressController;
use App\Http\Controllers\Api\Shipping\CitiesController;
use App\Http\Controllers\Api\Shipping\CompaniesController;
use App\Http\Controllers\Api\Shipping\CountriesController;
use App\Http\Controllers\Api\Shipping\StatesController;
use App\Http\Controllers\Api\Shipping\StreetsController;
use App\Http\Controllers\Api\Shipping\WarehousesController;
use App\Http\Controllers\Api\SignController;
use App\Http\Controllers\Api\SitesController;
use App\Http\Controllers\Api\Stores\CitiesController as StoreCitiesController;
use App\Http\Controllers\Api\Stores\StoresController;
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
     * Catalog
     */
    Route::group([
        'middleware' => 'auth:sanctum',
        'controller' => CategoriesController::class,
        'prefix' => 'catalog/categories',
    ], function () {
        Route::get('', 'index');
        Route::get('options', 'options');
        Route::post('store', 'store');
        Route::get('{id}', 'show');
        Route::delete('{id}', 'destroy');
    });

    Route::group([
        'middleware' => 'auth:sanctum',
        'prefix' => 'catalog',
    ], function () {
        /*
         * Brands
         */
        Route::post('brand/sorting', [BrandsController::class, 'sorting']);
        Route::delete('brand/mass-destroy', [BrandsController::class, 'massDestroy']);
        Route::get('brand/list', [BrandsController::class, 'list']);
        Route::apiResource('brand', BrandsController::class);

        /*
         * Manufacturers
         */
        Route::post('manufacturer/sorting', [ManufacturersController::class, 'sorting']);
        Route::delete('manufacturer/mass-destroy', [ManufacturersController::class, 'massDestroy']);
        Route::get('manufacturer/list', [ManufacturersController::class, 'list']);
        Route::apiResource('manufacturer', ManufacturersController::class);

        /*
         * Statuses
         */
        Route::post('status/sorting', [StatusesController::class, 'sorting']);
        Route::delete('status/mass-destroy', [StatusesController::class, 'massDestroy']);
        Route::get('status/list', [StatusesController::class, 'list']);
        Route::apiResource('status', StatusesController::class);

        /*
         * Stock
         */
        Route::post('stock/sorting', [StockController::class, 'sorting']);
        Route::delete('stock/mass-destroy', [StockController::class, 'massDestroy']);
        Route::get('stock/list', [StockController::class, 'list']);
        Route::apiResource('stock', StockController::class);

        /*
         * Options
         */
        Route::post('option/sorting', [OptionsController::class, 'sorting']);
        Route::get('option/list', [OptionsController::class, 'list']);
        Route::delete('option/mass-destroy', [OptionsController::class, 'massDestroy']);
        Route::get('option/list', [OptionsController::class, 'list']);
        Route::apiResource('option', OptionsController::class);

        /*
         * Option values
         */
        Route::post('option-value/sorting', [OptionValuesController::class, 'sorting']);
        Route::delete('option-value/mass-destroy', [OptionValuesController::class, 'massDestroy']);
        Route::get('option-value/list', [OptionValuesController::class, 'list']);
        Route::apiResource('option-value', OptionValuesController::class);

        /*
         * Saved filters
         */
        Route::post('saved-filter/sorting', [SavedFiltersController::class, 'sorting']);
        Route::apiResource('saved-filter', SavedFiltersController::class)->only(['index', 'store', 'destroy']);

        /*
         * Products
         */
        Route::get('product/filter', [ProductsController::class, 'filter']);
        Route::post('product/sorting', [ProductsController::class, 'sorting']);
        Route::post('product/modify', [ProductsController::class, 'modify']);
        Route::delete('product/mass-destroy', [ProductsController::class, 'massDestroy']);
        Route::get('product/list', [ProductsController::class, 'list']);
        Route::apiResource('product', ProductsController::class);

        /*
         * Reviews
         */
        Route::delete('review/mass-destroy', [ReviewsController::class, 'massDestroy']);
        Route::apiResource('review', ReviewsController::class);
    });

    /*
     * Equipment
     */
    Route::group([
        'middleware' => 'auth:sanctum',
        'controller' => EquipmentCategoriesController::class,
        'prefix' => 'equipment/categories',
    ], function () {
        Route::get('', 'index');
        Route::post('store', 'store');
        Route::get('{id}', 'show');
        Route::delete('{id}', 'destroy');
    });

    Route::group([
        'middleware' => 'auth:sanctum',
        'prefix' => 'equipment',
    ], function () {
        /*
         * Models
         */
        Route::post('model/sorting', [EquipmentModelsController::class, 'sorting']);
        Route::delete('model/mass-destroy', [EquipmentModelsController::class, 'massDestroy']);
        Route::get('model/list', [EquipmentModelsController::class, 'list']);
        Route::get('model/serials', [EquipmentModelsController::class, 'serials']);
        Route::apiResource('model', EquipmentModelsController::class);

        /*
         * Schemes
         */
        Route::post('scheme/sorting', [EquipmentSchemesController::class, 'sorting']);
        Route::delete('scheme/mass-destroy', [EquipmentSchemesController::class, 'massDestroy']);
        Route::get('scheme/list', [EquipmentSchemesController::class, 'list']);
        Route::apiResource('scheme', EquipmentSchemesController::class);

        /*
         * Serials
         */
        Route::post('serial/sorting', [EquipmentSerialsController::class, 'sorting']);
        Route::delete('serial/mass-destroy', [EquipmentSerialsController::class, 'massDestroy']);
        Route::get('serial/list', [EquipmentSerialsController::class, 'list']);
        Route::apiResource('serial', EquipmentSerialsController::class);
    });

    /*
     * Shipping
     */
    Route::group([
        'middleware' => 'auth:sanctum',
        'prefix' => 'shipping',
    ], function () {
        /*
         * Companies
         */
        Route::post('company/sorting', [CompaniesController::class, 'sorting']);
        Route::delete('company/mass-destroy', [CompaniesController::class, 'massDestroy']);
        Route::get('company/list', [CompaniesController::class, 'list']);
        Route::apiResource('company', CompaniesController::class);

        /*
         * Countries
         */
        Route::post('country/sorting', [CountriesController::class, 'sorting']);
        Route::delete('country/mass-destroy', [CountriesController::class, 'massDestroy']);
        Route::get('country/list', [CountriesController::class, 'list']);
        Route::apiResource('country', CountriesController::class);

        /*
         * States
         */
        Route::post('state/sorting', [StatesController::class, 'sorting']);
        Route::delete('state/mass-destroy', [StatesController::class, 'massDestroy']);
        Route::get('state/list', [StatesController::class, 'list']);
        Route::apiResource('state', StatesController::class);

        /*
         * Cities
         */
        Route::post('city/sorting', [CitiesController::class, 'sorting']);
        Route::delete('city/mass-destroy', [CitiesController::class, 'massDestroy']);
        Route::get('city/list', [CitiesController::class, 'list']);
        Route::get('city/find', [CitiesController::class, 'find']);
        Route::apiResource('city', CitiesController::class);

        /*
         * Warehouses
         */
        Route::post('warehouse/sorting', [WarehousesController::class, 'sorting']);
        Route::delete('warehouse/mass-destroy', [WarehousesController::class, 'massDestroy']);
        Route::get('warehouse/list', [WarehousesController::class, 'list']);
        Route::apiResource('warehouse', WarehousesController::class);

        /*
         * Streets
         */
        Route::post('street/sorting', [StreetsController::class, 'sorting']);
        Route::delete('street/mass-destroy', [StreetsController::class, 'massDestroy']);
        Route::get('street/list', [StreetsController::class, 'list']);
        Route::apiResource('street', StreetsController::class);

        /*
         * Address
         */
        Route::apiResource('address', AddressController::class)->only(['index']);
    });

    Route::group([
        'middleware' => 'auth:sanctum',
        'prefix' => 'orders',
    ], function () {
        /*
         * Statuses
         */
        Route::post('status/sorting', [OrderStatusesController::class, 'sorting']);
        Route::delete('status/mass-destroy', [OrderStatusesController::class, 'massDestroy']);
        Route::get('status/list', [OrderStatusesController::class, 'list']);
        Route::apiResource('status', OrderStatusesController::class);

        /*
         * Orders trash
         */
        Route::delete('trash/mass-destroy', [TrashController::class, 'massDestroy']);
        Route::post('trash/mass-restore', [TrashController::class, 'massRestore']);
        Route::post('trash/restore/{id}', [TrashController::class, 'restore']);
        Route::apiResource('trash', TrashController::class)->only(['index', 'destroy']);

        /*
         * Orders
         */
        Route::delete('order/mass-destroy', [OrdersController::class, 'massDestroy']);
        Route::apiResource('order', OrdersController::class);
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

    /*
     * Banners
     */
    Route::group([
        'middleware' => 'auth:sanctum',
    ], function () {
        Route::post('banners/sorting', [BannersController::class, 'sorting']);
        Route::delete('banners/mass-destroy', [BannersController::class, 'massDestroy']);
        Route::get('banners/list', [BannersController::class, 'list']);
        Route::apiResource('banners', BannersController::class);
    });

    /*
     * Equipment
     */
    Route::group([
        'middleware' => 'auth:sanctum',
        'controller' => EquipmentCategoriesController::class,
        'prefix' => 'equipment/categories',
    ], function () {
        Route::get('', 'index');
        Route::post('store', 'store');
        Route::get('{id}', 'show');
        Route::delete('{id}', 'destroy');
    });

    /*
     * Stores && cities
     */
    Route::group([
        'middleware' => 'auth:sanctum',
        'prefix' => 'stores',
    ], function () {
        /*
         * Cities
         */
        Route::post('city/sorting', [StoreCitiesController::class, 'sorting']);
        Route::apiResource('city', StoreCitiesController::class);
        /*
         * Stores
         */
        Route::post('store/sorting', [StoresController::class, 'sorting']);
        Route::delete('store/mass-destroy', [StoresController::class, 'massDestroy']);
        Route::get('store/list', [StoresController::class, 'list']);
        Route::apiResource('store', StoresController::class);
    });

});

// - ---------------------------------------------------------------------------------------------------------------------
