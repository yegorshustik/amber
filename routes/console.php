<?php

use App\Models\Catalog\Category;
use App\Models\Catalog\Option;
use App\Models\Catalog\OptionValue;
use App\Models\Catalog\Product;
use App\Services\ManticoreService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
