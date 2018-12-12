# Embryo View
PHP template engine and renderer for PSR-7 response. 

## Features
* PSR compatible.
* Alternative syntax.
* Views compiled and cached until they are modified.
* PSR-15 middleware for minify html.

## Requirements
* PHP >= 7.1
* A [PSR-7](https://www.php-fig.org/psr/psr-7/) http message implementation and [PSR-17](https://www.php-fig.org/psr/psr-17/) http factory implementation (ex. [Embryo-Http](https://github.com/davidecesarano/Embryo-Http))
* A PSR response emitter (ex. [Embryo-Emitter](https://github.com/davidecesarano/Embryo-Emitter))

## Installation
Using Composer:
```
$ composer require davidecesarano/embryo-routing
```

## Example
Create `Response` object, set `views` and `compilers` directory, create a `View` object. Render view with `render()` method  passing response, template and data. Finally, emit response.
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

## Usage

### Create and render views
If you want create a template with partials file, you can write this:
```html
<!-- header.php -->
<html>
    <head>
        <title>{{ $title }}</title>
    </head>
    <body>
```
```html
<!-- home.php -->
@include('header', ['title' => $title])
    
        <h1>{{ $title }}</h1>
    
    </body>
</html>
```
In this example you can use `@include(filename, data)` for include header.php in home.php passing data to file. Embryo View will compile the file by replacing the alternative syntax in PHP code. Finally, you may display page with render:
```php
$response = $view->render($response, 'home', ['title' => 'Hello World!']);
```

### Display data
You may display the contents of the name variable like so:
```
{{ $name }} // echo htmlentites($name)
```
If you want display html content use like so:
```
{{{ $html }}} // echo $html
```
### If statements
You may construct if statements using the `@if`, `@elseif`, `@else`, and `@endif`: directives. 
```
@if ($status == 1)
    Status is 1
@elseif ($status == 2)
    Status is 2
@else
    Status is {{ $status }}
@endif
```

### Loops
Embryo View provides simple directives for working with PHP's loop structures (`for`, `foreach` and `while`):
```
@for ($i = 0; $i < 10; $i++)
    Value is {{ $i }}
@endfor

@foreach ($users as $user)
    User id is {{ $user->id }}
@endforeach

@while ($user = $users)
    User id is {{ $user->id }}
@endwhile
```

### PHP
You can use the @php directive to execute a block of plain PHP within your template:
```
@php 
    $a = 1;
    echo  $a; 
@endphp
```