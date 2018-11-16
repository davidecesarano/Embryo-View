<?php 
    
    namespace Embryo\View;
    
    use Embryo\Http\Factory\StreamFactory;
    use Embryo\View\CompilerTrait;
    use Psr\Http\Message\ResponseInterface;
    use Psr\Http\Message\StreamFactoryInterface;

    class View 
    {
        use CompilerTrait;

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
            ob_start();
            try {

                //$data    = $this->setData($data);
                $content = $this->content($template);
                $content = $this->compile($content);
                $stream  = $this->stream($template, $content);
                $file    = $stream->getMetadata('uri');

                extract($data);
                require $file;
                
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
            ob_start();
            extract($data);
            require $this->templatePath.'/'.$template.'.php';
            ob_get_contents();
        }

        /**
         * Include template file with data.
         *
         * @param string $template
         * @param array $data
         * @return void
         * @throws InvalidArgumentExceptions
         * @throws RuntimeException
         */
        private function content(string $template)
        {
            $file = $this->templatePath.'/'.$template.'.php';
            if (!is_file($file)) {
                throw new \RuntimeException("View cannot render $file because the template does not exist");
            }

            $stream = $this->streamFactory->createStreamFromFile($file, 'r');
            return $stream->getContents();
        }

        private function setData(array $data)
        {
            if (isset($data['template'])) {
                throw new \InvalidArgumentException("Duplicate template key found");
            }

            foreach ($data as $key => $value)
            {
                $this->data[$key] = $value;
            }
        }

        private function getData()
        {
            return $this->data;
        }

        private function stream($template, $content)
        {
            $file   = $this->compilerPath.'/'.md5($template).'.php';
            $stream = $this->streamFactory->createStreamFromFile($file, 'w');
            $stream->write($content);
            return $stream;
        }
    }