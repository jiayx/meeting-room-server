<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use EasyWeChat\Foundation\Application;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 修改 storage_path
        if ($storagePath = config('app.storage_path')) {
            if (is_writable($storagePath)) {
                $this->app->useStoragePath($storagePath);
            }
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // 注册 easy wechat 实例
        $this->app->singleton(Application::class, function () {
            $options = [
                'mini_program' => [
                    'app_id'   => config('services.wechat.app_id'),
                    'secret'   => config('services.wechat.app_secret'),
                    // token 和 aes_key 开启消息推送后可见
                    'token'    => 'your-token',
                    'aes_key'  => 'your-aes-key'
                ],
            ];

            return new Application($options);
        });
    }
}
