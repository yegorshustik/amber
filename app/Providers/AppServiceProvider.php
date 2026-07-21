<?php

namespace App\Providers;

use App\Events\Catalog\ProductSavedEvent;
use App\Listeners\Catalog\ProductUpdateIndexListener;
use App\Models\Catalog\Category;
use App\Models\Configuration;
use App\Models\Page;
use App\Observers\BaseTreeObserver;
use App\Services\Localization;
use Carbon\CarbonImmutable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::addNamespace('amber', resource_path('views/amber'));

        Blade::componentNamespace('App\\View\\Components\\Amber', 'amber');

        Livewire::addNamespace(
            namespace: 'amber',
            classNamespace: 'App\\Livewire\\Amber',
            classPath: base_path('App/Livewire/Amber'),
            classViewPath: base_path('resources/views/amber/livewire')
        );

        $this->configureDefaults();

        $this->app->booted(function () {
            Page::observe(BaseTreeObserver::class);
        });

        Collection::macro('paginate', function ($perPage, $total = null, $page = null, $pageName = 'page') {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);

            return new LengthAwarePaginator(
                $total ? $this : $this->forPage($page, $perPage)->values(),
                $total ?: $this->count(),
                $perPage,
                $page,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        });
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null
        );

        $configuration = Configuration::mapped();

        $locale_prefix = locale_prefix() ?? (new Localization)->default()['locale'];
        foreach ($configuration as $key => $value) {
            config()->set('system_raw.'.$key, $value);

            if (is_array($value) && array_key_exists($locale_prefix, $value)) {
                config()->set('system.'.$key, $value[$locale_prefix]);
            } else {
                config()->set('system.'.$key, $value);
            }
        }
    }
}
