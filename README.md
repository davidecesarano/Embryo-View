# Embryo View

# Features
* PSR compatible.
* Views compiled and cached until they are modified.

# Requirements
* PHP >= 7.1
* A [PSR-7](https://www.php-fig.org/psr/psr-7/) http message implementation and [PSR-17](https://www.php-fig.org/psr/psr-17/) http factory implementation (ex. [Embryo-Http](https://github.com/davidecesarano/Embryo-Http))
* A PSR response emitter (ex. [Embryo-Emitter](https://github.com/davidecesarano/Embryo-Emitter))

# Installation
Using Composer:
```
$ composer require davidecesarano/embryo-routing
```

# Example
Create `Response` object, set views directory and compilers directory and create a `View` object. Render view with `render()` method  passing response, template and data. Finally, emit response.
```php
use Embryo\Http\Emitter\Emitter;
use Embryo\Http\Factory\{ResponseFactory, StreamFactory};
use Embryo\View\View;

$response      = (new ResponseFactory)->createResponse(200);
$templatePath  = __DIR__.DIRECTORY_SEPARATOR.'views';
$compilerPath  = __DIR__.DIRECTORY_SEPARATOR.'compilers';
$view          = new View($templatePath, $compilerPath);

$response = $view->render($response, 'page', ['message' => 'Hello World!', 'status' => 1]);

$emitter = new Emitter;
$emitter->emit($response);
```

You may quickly test this using the built-in PHP server going to http://localhost:8000.

```
$ cd example
$ php -S localhost:8000
```

# Usage