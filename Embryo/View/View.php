<?php 
    
    namespace Embryo\View;
    
    use Embryo\Http\Factory\StreamFactory;
    use Embryo\View\Traits\{CompilerTrait, StreamTrait};
    use Psr\Http\Message\ResponseInterface;
    use Psr\Http\Message\StreamFactoryInterface;

    class View 
    {
        use CompilerTrait;
        use StreamTrait;

        /**
         * @var string $templatePath
         */
        private $templatePath;

        /**
         * @var StreamFactoryInterface $streamFactory
         */
        private $streamFactory;

        /**
         * Set template path.
         * 
         * @param string $templatePath
         * @return self
         */
        public function __construct(string $templatePath, string $compilerPath, StreamFactoryInterface $streamFactory = null)
        {
            $this->templatePath  = rtrim($templatePath, '/');
            $this->compilerPath  = rtrim($compilerPath, '/');
            $this->streamFactory = ($streamFactory) ? $streamFactory : new StreamFactory;
        }
        
        /**
         * Rendering view scripts in PSR Response.
         *
         * @param ResponseInterface $response
         * @param string $template
         * @param array $data
         * @return ResponseInterface
         */
        public function render(ResponseInterface $response, string $template, array $data = []): ResponseInterface
        {
            try {

                ob_start();
                $this->include($template, $data);
                                
            } catch(\Throwable $e) {
                throw $e;
            } finally {
                $output = ob_get_clean();
            }
            
            $body = $response->getBody();
            $body->write($output);
            return $response->withBody($body);
        }

        public function include(string $template, array $data)
        {
            if (isset($data['template'])) {
                throw new \InvalidArgumentException("Duplicate template key found");
            }

            $content = $this->getContent($template);
            $content = $this->compile($content);
            $stream  = $this->setStream($template, $content);
            $file    = $stream->getMetadata('uri');

            extract($data);
            require $file;
        }
    }