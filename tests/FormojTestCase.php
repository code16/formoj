<?php

namespace Code16\Formoj\Tests;

use Orchestra\Testbench\TestCase;

class FormojTestCase extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
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