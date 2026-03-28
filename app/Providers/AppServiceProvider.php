<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\Paginator;
use App\Models\Setting;
use Illuminate\Support\Facades\Schema;

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
        // Tüm view'larda $settings erişilebilir olsun (child view @section'larında da)
        if (Schema::hasTable('settings')) {
            View::share('settings', Cache::remember('app.settings', 60, function () {
                return Setting::pluck('value', 'key')->toArray();
            }));
        } else {
            View::share('settings', []);
        }

        // Tüm sayfalamada özel pagination view'ını kullan
        Paginator::defaultView('vendor.pagination.default');
    }
}
