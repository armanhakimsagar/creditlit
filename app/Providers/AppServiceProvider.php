<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $studentName = 'student.profile';
        $SID = 'student.edit';
        $custId = 'customer.profile';

        $this->app->singleton('studentName', function () use ($studentName) {
            return $studentName;
        });

        $this->app->singleton('SID', function () use ($SID) {
            return $SID;
        });

        $this->app->singleton('custId', function () use ($custId) {
            return $custId;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Paginator::useBootstrap();
    }
}
