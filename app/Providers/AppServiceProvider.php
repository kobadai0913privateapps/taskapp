<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

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
        // if (\App::environment('production')) {
        //     \URL::forceScheme('https');
        // }
        Validator::extend('task_datetime', function($attribute, $value, $parameters, $validator){
            //現在の日付処理
            $now = Carbon::now();
            $now_datetime = $now->format('Y-m-d H:i:s');
            $task_datetime = Carbon::parse($value);
            $task_datetime = $task_datetime->format('Y-m-d H:i:s');
            return $now_datetime < $task_datetime;
        });
    }
}
