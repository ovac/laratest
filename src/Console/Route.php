<?php

namespace Yab\LaraTest\Console;

use Exception;
use Illuminate\Console\Command;
use Yab\LaraTest\Services\RouteTestService;
use Yab\LaraTest\Services\FileService;

class Route extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'laratest:route {route}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a simple Unit test based on a class';

    /**
     * The route service.
     *
     * @var UnitTestService
     */
    protected $routeTestService;

    /**
     * The file service.
     *
     * @var UnitTestService
     */
    protected $fileService;

    /**
     * Route Constructor.
     *
     * @param RouteTestService $routeTestService
     * @param FileService $fileService
     */
    public function __construct(
        RouteTestService $routeTestService,
        FileService $fileService
    ) {
        parent::__construct();
        $this->service = $routeTestService;
        $this->fileService = $fileService;
    }

    /**
     * Generate a CRUD stack.
     *
     * @return mixed
     */
    public function handle()
    {
        $route = $this->argument('route');

        $routes = file_get_contents(app()->basePath().'/routes/web.php');

        $matches = $this->fileService->getLine($routes, $route, true);

        $config = [
            '_class_' => ucfirst($route).'IntegrationTest',
            '_methods_' => $matches
        ];

        $test = $this->service->createFromStub($config);

        if (file_put_contents(app()->basePath().'/tests/'.$test['filename'], $test['content'])) {
            $this->info("\n".'An integration test file has been generated for: '.$route."\n");
        } else {
            $this->warning("\n".'We were unable to generate a test for: '.$route."\n");
        }
    }
}
