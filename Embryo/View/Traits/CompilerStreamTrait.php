<?php 

    /**
     * CompilerStreamTrait
     * 
     * @author Davide Cesarano <davide.cesarano@unipegaso.it>
     * @link   https://github.com/davidecesarano/embryo-view 
     */
    
    namespace Embryo\View\Traits;

    use Embryo\View\Exceptions\{ TemplateCompilerException, TemplateNotFoundException};
    
    trait CompilerStreamTrait
    {
        /**
         * @var string $templateFile
         */
        private $templateFile;

        /**
         * Get content from template file.
         *
         * @param string $template
         * @return string
         * @throws TemplateNotFoundException
         */
        protected function getContent(string $template): string
        {
            $extension = ($this->extension === '') ? '' : '.'.$this->extension;
            $file      = $this->templatePath.DIRECTORY_SEPARATOR.$template.$extension.'.php';
            if (!is_file($file)) {
                throw new TemplateNotFoundException("View cannot render $file because the template does not exist");
            }

            $this->setTemplateFile($file);
            $stream = $this->streamFactory->createStreamFromFile($file, 'r');
            return $stream->getContents();
        }        

        /**
         * Set content in compiler file and 
         * return it.
         * 
         * If the compiled file is older than a the template 
         * file recompiled the view and store it as a new 
         * cached version of the source file.
         *
         * @param string $template
         * @param string $content
         * @return string
         */
        protected function setContent(string $template, string $content): string
        {
            $extension = ($this->extension === '') ? '' : '.'.$this->extension;
            $templateFile = $this->templatePath.DIRECTORY_SEPARATOR.$template.$extension.'.php';
            $compilerFile = $this->compilerPath.DIRECTORY_SEPARATOR.md5($template.$extension).'.php';
            
            try {

                if (!file_exists($compilerFile)) {
                    $stream = $this->streamFactory->createStreamFromFile($compilerFile, 'w');
                    $stream->write($content);
                } else {
                    $stream = $this->streamFactory->createStreamFromFile($compilerFile, 'r');
                }

                if (filemtime($templateFile) > filemtime($compilerFile)) {
                    $stream = $this->streamFactory->createStreamFromFile($compilerFile, 'w');
                    $stream->write($content);
                }

            } catch (\Exception $e) {
                throw new TemplateCompilerException($e->getMessage());
            }

            return $stream->getMetadata('uri');
        }

        /**
         * Set template file.
         * 
         * @param string $templateFile
         * @return void
         */
        private function setTemplateFile(string $templateFile)
        {
            $this->templateFile = $templateFile;
        }

        /**
         * Get template file.
         * 
         * @return string
         */
        protected function getTemplateFile(): string 
        {
            return $this->templateFile;
        }
    }