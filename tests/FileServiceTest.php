<?php

use Yab\LaraTest\Services\FileService;

class FileServiceTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->service = app(FileService::class);
    }

    public function testGetLineSingle()
    {
        $content = "
<?php

namespace Foo;

use Foo\Class;

class FooBar extends TestCase
{
    public function FooMoo() {
        return 'foo';
    }
}
";
        $test = $this->service->getLine($content, 'namespace');
        $this->assertEquals($test, 'namespace Foo;');
    }

    public function testGetLineMultiple()
    {
        $content = "
<?php

Route::get('login', 'FooController');
Route::post('login', 'FooController');
Route::post('logout', 'FooController');
Route::resource('users', 'FooController');
";
        $test = $this->service->getLine($content, 'login', true);
        $this->assertTrue(is_array($test));
        $this->assertEquals($test[0], "Route::get('login', 'FooController');");
    }
}
