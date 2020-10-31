<?php 

    /**
     * TemplateRenderException
     * 
     * @author Davide Cesarano <davide.cesarano@unipegaso.it>
     * @link   https://github.com/davidecesarano/embryo-view 
     */

    namespace Embryo\View\Exceptions;

    class TemplateRenderException extends \Exception
    {
        /**
         * @var string $file
         */
        protected $file;

        /**
         * @var int $line
         */
        protected $line;

        /**
         * Set error.
         * 
         * @param \Throwable $error
         * @param string $template
         */
        public function __construct(\Throwable $error, string $template)
        {
            $this->file = $template;
            $this->line = $error->getLine();
            parent::__construct($error->getMessage());
        }
    }