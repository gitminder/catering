<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
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
        Validator::extend('exists_not_deleted', function ($attribute, $value, $parameters) {
            [$table, $column] = $parameters;

            return DB::table($table)
                ->where($column, $value)
                ->where('deleted', 0)
                ->exists();
        });
    }
}
