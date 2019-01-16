<?php 

    /**
     * CompilerStreamTrait
     * 
     * @author Davide Cesarano <davide.cesarano@unipegaso.it>
     * @link   https://github.com/davidecesarano/embryo-view 
     */
    
    namespace Embryo\View\Traits;
    
    trait CompilerStreamTrait
    {
        /**
         * Get content from template file.
         *
         * @param string $template
         * @return string
         * @throws RuntimeException
         */
        protected function getContent(string $template): string
        {
            $extension = ($this->extension === '') ? '' : '.'.$this->extension;
            $file      = $this->templatePath.'/'.$template.$extension.'.php';
            if (!is_file($file)) {
                throw new \RuntimeException("View cannot render $file because the template does not exist");
            }

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
            $extension    = ($this->extension === '') ? '' : '.'.$this->extension;
            $templateFile = $this->templatePath.'/'.$template.$extension.'.php';
            $compilerFile = $this->compilerPath.'/'.md5($template.$extension).'.php';

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

            return $stream->getMetadata('uri');
        }
    }