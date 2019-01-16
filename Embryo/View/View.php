<?php 
    
    /**
     * View
     * 
     * @author Davide Cesarano <davide.cesarano@unipegaso.it>
     * @link   https://github.com/davidecesarano/embryo-view 
     */
    
    namespace Embryo\View;
    
    use Embryo\Http\Factory\StreamFactory;
    use Embryo\View\Traits\{CompilerReplaceTrait, CompilerStreamTrait};
    use Psr\Http\Message\ResponseInterface;
    use Psr\Http\Message\StreamFactoryInterface;

    class View 
    {
        use CompilerReplaceTrait;
        use CompilerStreamTrait;

        /**
         * @var string $templatePath
         */
        private $templatePath;

        /**
         * @var string $templatePath
         */
        private $compilerPath;

        /**
         * @var StreamFactoryInterface $streamFactory
         */
        private $streamFactory;

        /**
         * @var string $extension
         */
        private $extension = 'embryo';

        /**
         * Set template path, compiler path and
         * stream factory.
         * 
         * @param string $templatePath
         */
        public function __construct(string $templatePath, string $compilerPath, StreamFactoryInterface $streamFactory = null)
        {
            $this->templatePath  = rtrim($templatePath, '/');
            $this->compilerPath  = rtrim($compilerPath, '/');
            $this->streamFactory = ($streamFactory) ? $streamFactory : new StreamFactory;
        }

        /**
         * Set file pre extension.
         *
         * @example home.embryo.php
         * @param string $extension
         * @return self
         */
        public function setExtension(string $extension = 'embryo'): self
        {
            $this->extension = 'embryo';
            return $this;
        }
        
        /**
         * Render view in PSR Response.
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
                $output = ob_get_clean();

            } catch(\Throwable $e) {
                ob_end_clean();
                throw $e;
            }
            
            $body = $response->getBody();
            $body->write($output);
            return $response->withBody($body);
        }

        /**
         * Get content from view, compile it for replacing
         * alternative syntax, extract data 
         * and set compiler file.
         *
         * @param string $template
         * @param array $data
         * @return void
         */
        public function include(string $template, array $data = [])
        {
            if (isset($data['template'])) {
                throw new \InvalidArgumentException("Duplicate template key found");
            }

            $content = $this->getContent($template);
            $output  = $this->compile($content);
            $file    = $this->setContent($template, $output);

            extract($data);
            require $file;
        }
    }