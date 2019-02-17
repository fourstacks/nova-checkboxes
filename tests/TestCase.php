<?php

namespace Fourstacks\NovaCheckboxes\Tests;

use Orchestra\Testbench\TestCase as BaseTest;

class TestCase extends BaseTest
{
     /**
     * Get the service providers for the package.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            'Fourstacks\NovaCheckboxes\FieldServiceProvider',
        ];
    }

}
