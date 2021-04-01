<?php 
    
    /**
     * View
     * 
     * @author Davide Cesarano <davide.cesarano@unipegaso.it>
     * @link   https://github.com/davidecesarano/embryo-view 
     */
    
    namespace Embryo\View;
    
    use Embryo\Http\Factory\StreamFactory;
    use Embryo\View\Exceptions\{InvalidTemplateDataException, TemplateRenderException, TemplateNotFoundException };
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
         * @var string $compilerPath
         */
        private $compilerPath;

        /**
         * @var StreamFactoryInterface $streamFactory
         */
        private $streamFactory;

        /**
         * @var string $extension
         */
        private $extension = '';

        /**
         * Set template path, compiler path and
         * stream factory.
         * 
         * @param string $templatePath
         */
        public function __construct(string $templatePath, string $compilerPath, StreamFactoryInterface $streamFactory = null)
        {
            $this->templatePath  = rtrim($templatePath, DIRECTORY_SEPARATOR);
            $this->compilerPath  = rtrim($compilerPath, DIRECTORY_SEPARATOR);
            $this->streamFactory = ($streamFactory) ? $streamFactory : new StreamFactory;
        }

        /**
         * Set file pre extension.
         *
         * @example home.embryo.php
         * @param string $extension
         * @return self
         */
        public function setExtension(string $extension = ''): self
        {
            $this->extension = $extension;
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
            ob_start();
            $this->include($template, $data);
            $output = ob_get_clean() ?: '';
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
         * @throws InvalidTemplateDataException
         * @throws TemplateNotFoundException
         * @throws TemplateRenderException
         */
        public function include(string $template, array $data = [])
        {
            if (isset($data['template'])) {
                throw new InvalidTemplateDataException("Duplicate template key found");
            }

            $content = $this->getContent($template);
            $output  = $this->compile($content);
            $file    = $this->setContent($template, $output);
            $templateFile = $this->getTemplateFile();

            try {
                extract($data);
                require $file;
            } catch (\Throwable $e) {
                ob_get_clean();
                throw new TemplateRenderException($e, $templateFile);
            }
            
        }
    }