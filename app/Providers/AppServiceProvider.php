<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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
        Carbon::setLocale('ar');

        Gate::before(function ($user, $ability) {
            if ($user->super_admin) {
                return true;
            }
        });

        foreach (require base_path('data/abilities.php') as $ability => $label) {
            Gate::define($ability, function ($user) use ($ability) {
                return $user->roles()->whereHas('abilities', function ($query) use ($ability) {
                    $query->where('ability', $ability);
                })->exists();
            });
        }
    }
}
