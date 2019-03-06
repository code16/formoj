<?php

namespace Code16\Formoj;

use Code16\Formoj\Console\SendFormojNotificationsForYesterday;
use Illuminate\Support\ServiceProvider;

class FormojServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/api.php');

        $this->loadMigrationsFrom(dirname(__DIR__) . "/database/migrations");

        $this->loadTranslationsFrom(dirname(__DIR__) . '/resources/lang', 'formoj');

        $this->loadViewsFrom(dirname(__DIR__) . '/resources/views', 'formoj');

        $this->commands([
            SendFormojNotificationsForYesterday::class,
        ]);

        $this->publishes([
            dirname(__DIR__) . '/lang' => resource_path('lang/vendor/formoj')
        ], 'lang');

        $this->publishes([
            __DIR__.'/config.php' => config_path('formoj.php'),
        ], 'config');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config.php', 'formoj'
        );
    }
}