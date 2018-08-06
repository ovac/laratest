<?php

use Yab\LaraTest\Services\UnitTestService;

class UnitTest extends TestCase
{
    protected $content;

    public function setUp()
    {
        parent::setUp();
        $this->service = app(UnitTestService::class);
        $this->content = "
<?php

namespace Foo;

use Foo\Class;

class FooBar extends TestCase
{
    public function fooMoo() {
        return 'foo';
    }

    public function ohMonDieu()
    {
        return 'foo';
    }
}
";
    }

    public function testGetNamespace()
    {
        $test = $this->service->getNamespace($this->content);
        $this->assertEquals($test, 'Foo');
    }

    public function testGetTestClass()
    {
        $test = $this->service->getTestClass($this->content);
        $this->assertEquals($test, 'FooBarTest');
    }

    public function testGetClass()
    {
        $test = $this->service->getClass($this->content);
        $this->assertEquals($test, 'FooBar');
    }

    public function testGetFunctions()
    {
        $test = $this->service->getFunctions($this->content);
        $this->assertContains('testFooMoo', $test);
    }

    public function testCreateFromStub()
    {
        $config = [
            '_class_namespace_' => $this->service->getNamespace($this->content),
            '_test_class_' => $this->service->getTestClass($this->content),
            '_class_' => $this->service->getClass($this->content),
            '_methods_' => $this->service->getFunctions($this->content),
        ];

        $test = $this->service->createFromStub($config, 'unit.stub');

        $this->assertContains('testFooMoo', $test['content']);
        $this->assertContains('testOhMonDieu', $test['content']);
        $this->assertContains('this->class->ohMonDieu', $test['content']);
    }
}
