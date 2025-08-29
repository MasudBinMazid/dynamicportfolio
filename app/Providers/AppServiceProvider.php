<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

use App\Models\ContactInfo;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // 1) Never touch DB during CLI (composer/artisan) OR when SKIP_DB_ON_BOOT=1
        if ($this->app->runningInConsole() || (bool) env('SKIP_DB_ON_BOOT', false)) {
            return;
        }

        try {
            // 2) Only proceed if a real DB connection is possible
            DB::connection()->getPdo();

            // 3) Now it's safe to call Schema/Model
            if (Schema::hasTable('contact_infos')) {
                $contact = Cache::remember('contactInfo:first', 3600, function () {
                    return ContactInfo::query()->first();
                });
                view()->share('contactInfo', $contact);
            }
        } catch (\Throwable $e) {
            // If DB isn't reachable yet, never crash the app (or your build)
            Log::warning('Skipped contactInfo share (DB not ready): '.$e->getMessage());
        }
    }
}
