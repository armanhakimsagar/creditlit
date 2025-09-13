<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class SettingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        $reportFontSize = (DB::table('companies')
                ->select('companies.ReportFontSize')
                ->first())->ReportFontSize;
        view()->share('reportFontSize', $reportFontSize);

        $company = DB::table('companies')->first();
        view()->share('companyDetails', $company);
        $this->app->instance('companyDetails', $company);

        $sid_config = DB::table('sid_config')->first();
        view()->share('sidConfig', $sid_config);

        $tf_id = DB::table('products')->where('name','Tuition Fees')->value('id');
        view()->share('tf_id', $tf_id);
        // $this->app->instance('tf_id', $tf_id);
        $this->app->singleton('tf_id', function () use ($tf_id) {
            return $tf_id;
        });
    }
}
