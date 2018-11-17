<?php 

    require __DIR__ . '/../vendor/autoload.php';
    
    use Embryo\Http\Emitter\Emitter;
    use Embryo\Http\Factory\{ResponseFactory, StreamFactory};
    use Embryo\View\View;

    $response      = (new ResponseFactory)->createResponse(200);
    $templatePath  = __DIR__.DIRECTORY_SEPARATOR.'views';
    $compilerPath  = __DIR__.DIRECTORY_SEPARATOR.'compilers';
    $view          = new View($templatePath, $compilerPath);

    $response = $view->render($response, 'page', ['message' => 'Hello test', 'a' => 2]);

    $emitter = new Emitter;
    $emitter->emit($response);