# LaraTest

**LaraTest** - A elegantly simple way to generate tests for Laravel.

Writing tests can be a joy, at least writing ones that work can be. TDD is obviously ideal but sometimes it just ends up being last on the client's priority list. With LaraTest you can generate Unit tests from existing Classes or Integration tests from Routes.

**Author(s):**
* [Matt Lantz](https://github.com/mlantz) ([@mattylantz](http://twitter.com/mattylantz), matt at yabhq dot com)

## Requirements

1. PHP 5.6+
3. Laravel 5.3+

### Composer
Start a new Laravel project:
```php
composer create-project laravel/laravel your-project-name
```

Then run the following to add LaraTest
```php
composer require yab/laratest
```

### Providers

```php
Yab\LaraTest\LaraTestProvider::class
```

### Use Cases

Here are some basic use cases.

Generate a unit test from a class:
```
php artisan laratest:unit path/to/class/to/test.php
```

Generate an integration test from a route:
```
php artisan laratest:route users
```

## License
LaraTest is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

### Bug Reporting and Feature Requests
Please add as many details as possible regarding submission of issues and feature requests

### Disclaimer
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
