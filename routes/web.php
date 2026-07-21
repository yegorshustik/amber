<?php

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

Route::get('robots.txt', function () {
    $default = 'User-agent: *
Disallow: /';

    return response(config('system.seo.robots-txt') ?? $default, Response::HTTP_OK)
        ->header('Content-Type', 'text/plain');
});

Route::view('cms', 'backend');
Route::view('cms/{url}', 'backend')->where('url', '.*');
