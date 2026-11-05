<?php

namespace App\Providers;

use App\Models\Ad;
use Illuminate\Support\Facades\View;
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
        // $ads['banner'|'popup'|'sidebar'|'inline'] = the newest live ad of that
        // type (or missing). Shared with the public layout and the page views
        // (a page's sections render before its layout), queried once per request.
        View::composer(['layouts.public', 'public.*'], function ($view) {
            $request = request();
            if (! $request->attributes->has('live_ads')) {
                $request->attributes->set('live_ads', Ad::live()->latest()->get()->unique('type')->keyBy('type'));
            }
            $view->with('ads', $request->attributes->get('live_ads'));
        });
    }
}
