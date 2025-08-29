<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;
use App\Models\ContactInfo;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Never touch the DB during console tasks (composer scripts, caching, etc.)
        if (App::runningInConsole()) {
            return;
        }

        try {
            // Ensure a connection is actually available before calling Schema/Models
            DB::connection()->getPdo();

            if (Schema::hasTable('contact_infos')) {
                // Cache to avoid a query on every request
                $contact = Cache::remember('contactInfo:first', 3600, function () {
                    return ContactInfo::query()->first();
                });

                view()->share('contactInfo', $contact);
            }
        } catch (\Throwable $e) {
            // If DB isn’t up yet, don’t crash the app (or your build)
            Log::warning('Skipping contactInfo share; DB not ready: '.$e->getMessage());
        }
    }
}
