<?php

namespace Code16\Formoj;

use Illuminate\Support\ServiceProvider;

class FormojServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/api.php');

        $this->loadMigrationsFrom(dirname(__DIR__) . "/database/migrations");
    }

    public function register()
    {
    }
}