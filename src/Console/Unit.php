<?php

namespace Yab\LaraTest\Console;

use Exception;
use Illuminate\Console\Command;
use Yab\LaraTest\Services\UnitTestService;

class Unit extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'laratest:unit {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a unit test template based on a file';

    /**
     * The app service.
     *
     * @var UnitTestService
     */
    protected $unitTestService;

    /**
     * Unit Constructor.
     *
     * @param UnitTestService $unitTestService
     */
    public function __construct(UnitTestService $unitTestService)
    {
        parent::__construct();
        $this->service = $unitTestService;
    }

    /**
     * Generate a CRUD stack.
     *
     * @return mixed
     */
    public function handle()
    {
        $file = $this->argument('file');

        $contents = file_get_contents($file);

        $config = [
            '_class_namespace_' => $this->service->getNamespace($contents),
            '_test_class_' => $this->service->getTestClass($contents),
            '_class_' => $this->service->getClass($contents),
            '_methods_' => $this->service->getFunctions($contents),
        ];

        $test = $this->service->createFromStub($config, 'unit.stub');

        if (file_put_contents(app()->basePath().'/tests/'.$test['name'].'.php', $test['content'])) {
            $this->info("\n".'A unit test file has been generated for: '.$file."\n");
        } else {
            $this->warning("\n".'We were unable to generate a test for: '.$file."\n");
        }
    }
}
