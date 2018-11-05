<?php 
    
    namespace Embryo\View;
    
    use Psr\Http\Message\ResponseInterface;

    class View 
    {
        /**
         * @var string $templatePath
         */
        private $templatePath;

        /**
         * Set template path.
         * 
         * @param string $templatePath
         * @return self
         */
        public function __construct(string $templatePath)
        {
            $this->templatePath = rtrim($templatePath, '/');
            return $this;
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
                $output = ob_get_clean();

            } catch(\Throwable $e) {
                ob_end_clean();
                throw $e;
            }
            return $response->write($output);
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
        public function include(string $template, array $data = [])
        {
            if (isset($data['template'])) {
                throw new \InvalidArgumentException("Duplicate template key found");
            }
    
            $file = $this->templatePath.'/'.$template.'.php';
            if (!is_file($file)) {
                throw new \RuntimeException("View cannot render $file because the template does not exist");
            }

            extract($data);
            include $file;
        }
    }