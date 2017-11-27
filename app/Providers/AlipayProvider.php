<?php

namespace App\Providers;

use App\Services\Alipay\AliClient;
use Illuminate\Support\ServiceProvider;

class AlipayProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $source = realpath(__DIR__.'/publishes/alipay_config.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                $source => config_path('alipay.php'),
            ]);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(AliClient::class, function ($app) {
            return new AliClient(config('alipay'));
        });

    }

    /**
     * 获取提供器提供的服务。
     *
     * @return array
     */
    public function provides()
    {
        return [AliClient::class];
    }
}
