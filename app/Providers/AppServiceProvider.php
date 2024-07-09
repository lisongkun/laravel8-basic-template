<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Opcodes\LogViewer\Facades\LogViewer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        // Log-Viewer的授权逻辑
        LogViewer::auth(function ($request) {
            // return true to allow viewing the Log Viewer.
            return true;
        });

        // 开发环境打印 SQL
        if (config('app.env') === 'local') {
            \DB::listen(function ($query) {
                $sql = $query->sql;
                $bindings = $query->bindings;
                $time = $query->time;
                $sql = str_replace('?', "'%s'", $sql);
                $fullSql = vsprintf($sql, $bindings);
                \Log::debug('SQL', ['sql' => $fullSql, 'time' => $time]);
            });
        }
    }
}
