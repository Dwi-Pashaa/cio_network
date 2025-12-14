<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer([
            'pages.pages.show',
            'pages.mac-address.index',
        ], function ($view) {

            $isMacValidationActive = DB::table('setting')
                ->where('key', 'mac_address_validation')
                ->value('value') === 'active';

            $view->with('isMacValidationActive', $isMacValidationActive);
        });
    }
}
