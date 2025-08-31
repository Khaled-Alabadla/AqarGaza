<?php

namespace App\Providers;

use App\Models\Contact;
use App\Models\Favorite;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('settings', function () {
            return Setting::pluck('value', 'key')->toArray();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Cache::remember('settings', 3600, function () {
        //     return Setting::pluck('value', 'key')->toArray();
        // });



        View::composer('dashboard.layouts.main-sidebar', function ($view) {
            $messages_count = Contact::where('is_open', 0)->count();
            $view->with('contact_messages', $messages_count);
        });

        View::composer('front.components.favorites', function ($view) {
            $favorites = Favorite::all();
            $view->with('favorites', $favorites);
        });
    }
}
