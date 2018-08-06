<?php

use Yab\LaraTest\Services\RouteTestService;

class RouteTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->service = app(RouteTestService::class);
    }

    public function testCreateFromStub()
    {
        $config = [
            '_methods_' => [
                "Route::get('login', 'FooController');",
                "Route::post('login', 'FooController');"
            ],
            '_class_' => 'SupermanIntegrationTest'
        ];

        $test = $this->service->createFromStub($config);

        $this->assertContains('testGetLogin', $test['content']);
        $this->assertContains('testPostLogin', $test['content']);
        $this->assertContains('SupermanIntegrationTest', $test['content']);
        $this->assertContains('DatabaseMigrations', $test['content']);
    }

    public function testFindAction()
    {
        $route = "Route::post('login', 'FooController');";
        $test = $this->service->findAction($route);

        $this->assertEquals('post', $test);
    }

    public function testGetUri()
    {
        $route = "Route::post('login', 'FooController');";
        $test = $this->service->getUri($route, 'post');

        $this->assertEquals('login', $test);
    }

    public function testCreateMethod()
    {
        $action = 'get';
        $uri = 'login';

        $test = $this->service->createMethod($action, $uri);

        $this->assertContains('testGetLogin', $test);
        $this->assertContains("this->call('get', 'login');", $test);
    }

    public function testCreateMethodsFromStub()
    {
        $routes = [
            "Route::get('login', 'FooController');",
            "Route::post('login', 'FooController');",
        ];

        $test = $this->service->createMethodsFromStub($routes);

        $this->assertContains('testGetLogin', $test);
        $this->assertContains('testPostLogin', $test);
    }
}
