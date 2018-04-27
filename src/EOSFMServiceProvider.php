<?php

namespace EOSFM\Framework;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use EOSFM\Framework\Middleware\EOSCheck;


class EOSFMServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $router -> middlewareGroup('EOSCheck', [EOSCheck::class]);
        $this -> loadMigrationsFrom(__DIR__.'/Migrations');
        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\eosinit::class,
            ]);
        }
//        $this -> loadTranslationsFrom(__DIR__.'/Translations', 'codex');
    }

    /**
     * 注册服务提供者
     *
     * @return void
     */
    public function register()
    {
        $this -> publishFiles();
        $this -> loadRoutesFrom(__DIR__. '/Routes/web.php');
    }

    /**
     * 将配置文件发布到程序中
     *
     * @return void
     */
    protected function publishFiles()
    {
//        $this -> publishes([
//            __DIR__. '/Assets/'. C('CORE_THEME', C('CORE_THEME', config('codex.theme', 'default'))) => public_path('resources/codex/'. C('CORE_THEME', C('CORE_THEME', config('codex.theme', 'default')))),
//        ], 'codex');

        $this -> loadViewsFrom(__DIR__. '/Templates/'. config('admin.theme', 'default'), 'eosadm');

        $this -> publishes([
            __DIR__. '/Config/admin.php' => config_path('admin.php'),
        ], 'eosadm');
//
        $this -> publishes([
            __DIR__. '/Templates/assets' => public_path('resources'),
        ], 'eosadm');
//
//        $this -> publishes([
//            __DIR__. '/Assets/default' => public_path('resources/codex/default'),
//        ], 'dev-codex');
//
//        $this -> publishes([
//            __DIR__. '/Assets/global' => public_path('resources/codex/global'),
//        ], 'dev-core');


    }
}
