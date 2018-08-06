<?php

use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    protected $app;

    protected function getEnvironmentSetUp($app)
    {
        $app->make('Illuminate\Contracts\Http\Kernel');
    }

    protected function getPackageProviders($app)
    {
        return [
            \Yab\LaraTest\LaraTestProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [];
    }

    public function setUp()
    {
        parent::setUp();

        $this->artisan('vendor:publish', [
            '--provider' => 'Yab\LaraTest\LaraTestProvider',
            '--force'    => true,
        ]);

        $this->withoutMiddleware();
        $this->withoutEvents();
    }
}
