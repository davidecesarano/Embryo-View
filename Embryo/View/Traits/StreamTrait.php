<?php 

    namespace Embryo\View\Traits;

    use Psr\Http\Message\StreamInterface;
    
    trait StreamTrait
    {
        /**
         * Include template file with data.
         *
         * @param string $template
         * @param array $data
         * @return void
         * @throws InvalidArgumentExceptions
         * @throws RuntimeException
         */
        protected function getContent(string $template): string
        {
            $file = $this->templatePath.'/'.$template.'.php';
            if (!is_file($file)) {
                throw new \RuntimeException("View cannot render $file because the template does not exist");
            }

            $stream = $this->streamFactory->createStreamFromFile($file, 'r');
            return $stream->getContents();
        }        

        protected function setStream(string $template, string $content): StreamInterface
        {
            $file   = $this->compilerPath.'/'.md5($template).'.php';
            $stream = $this->streamFactory->createStreamFromFile($file, 'w');
            $stream->write($content);
            return $stream;
        }
    }