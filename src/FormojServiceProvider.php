<?php

namespace Code16\Formoj;

use Illuminate\Support\ServiceProvider;

class FormojServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/api.php');

        $this->loadMigrationsFrom(dirname(__DIR__) . "/database/migrations");

        $this->loadTranslationsFrom(dirname(__DIR__) . '/resources/lang', 'formoj');

        $this->loadViewsFrom(dirname(__DIR__) . '/resources/views', 'formoj');

        $this->publishes([
            dirname(__DIR__) . '/lang' => resource_path('lang/vendor/formoj')
        ]);
    }

    public function register()
    {
    }
}