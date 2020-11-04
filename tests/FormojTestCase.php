<?php

namespace Code16\Formoj\Tests;

use Illuminate\Database\Eloquent\Factory;
use Orchestra\Testbench\TestCase;

class FormojTestCase extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->make(Factory::class)
            ->load(__DIR__ . '/../database/factories');
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [\Code16\Formoj\FormojServiceProvider::class];
    }
}