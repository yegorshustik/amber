<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\ConfigurationCollection;
use App\Models\Configuration;
use Illuminate\Http\Request;

class ConfigurationController extends Controller
{
    public function index(): ConfigurationCollection
    {
        return new ConfigurationCollection(Configuration::get());
    }

    public function store(Request $request)
    {
        $params = $request->input('param');

        foreach ($params as $slug => $content) {
            Configuration::updateOrCreate([
                'site_id' => site()->id,
                'slug' => $slug,
            ], [
                'site_id' => site()->id,
                'slug' => $slug,
                'content' => $content,
            ]);
        }

        return new ConfigurationCollection(Configuration::get());
    }
}
