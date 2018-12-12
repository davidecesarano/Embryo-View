<?php 
    
    /**
     * MinifyHtmlMiddleware
     * 
     * @author Davide Cesarano <davide.cesarano@unipegaso.it>
     * @link   https://github.com/davidecesarano/embryo-view 
     */

    namespace Embryo\View\Middleware;
    
    use Embryo\Http\Factory\StreamFactory;
    use Psr\Http\Message\{ServerRequestInterface, ResponseInterface};
    use Psr\Http\Server\{MiddlewareInterface, RequestHandlerInterface};
    
    class MinifyHtmlMiddleware implements MiddlewareInterface 
    {   
        /**
         * Process a server request and return a response.
         *
         * @param ServerRequestInterface $request
         * @param RequestHandlerInterface $handler
         * @return ResponseInterface
         */
        public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
        {
            $response = $handler->handle($request);
            if (stripos($response->getHeaderLine('Content-Type'), 'text/html') === 0) {
                
                $stream = (new StreamFactory)->createStream();
                $stream->write($this->minify((string) $response->getBody()));
                return $response->withBody($stream);

            }
            return $response;
        }

        /**
         * Minify html.
         *
         * @param string $content
         * @return string
         */
        private function minify(string $content): string
        {
            return preg_replace('/\s+/', ' ', $content);
        }
    }