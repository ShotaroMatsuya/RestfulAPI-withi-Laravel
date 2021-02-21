<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

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
    {   //utf8mb4は1020 characters まで対応しているがmysqlは767なので以下を記述することでエラーを避けることができる
        Schema::defaultStringLength(191);
    }
}
