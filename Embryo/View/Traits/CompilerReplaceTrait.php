<?php 

    /**
     * CompilerReplaceTrait
     * 
     * @author Davide Cesarano <davide.cesarano@unipegaso.it>
     * @link   https://github.com/davidecesarano/embryo-view 
     */
    
    namespace Embryo\View\Traits;

    trait CompilerReplaceTrait
    {
        /**
         * @var array $find
         */
        private $find = [
            '/@include\((.*)\)/m',
            '/@if\s?\((.*)\)/m',
            '/@else/m',
            '/@elseif\s?\((.*)\)/m',
            '/@endif/m',
            '/@foreach\s?\((.*)\)/m',
            '/@endforeach/m',
            '/@for\s?\((.*)\)/m',
            '/@endfor/m',
            '/@while\s?\((.*)\)/m',
            '/@endwhile/m',
            '/{{{\s([\$a-zA-Z_0-9\s:?]+)\s}}}/m',
            '/{{\s([\$a-zA-Z_0-9\s:?]+)\s}}/m',
            '/@php\s(.*)/m'
        ];

        /**
         * @var array $replace
         */
        private $replace = [
            '<?php $this->include($1); ?>',
            '<?php if($1): ?>',
            '<?php else: ?>',
            '<?php elseif($1): ?>',
            '<?php endif; ?>',
            '<?php foreach($1): ?>',
            '<?php endforeach; ?>',
            '<?php for($1): ?>',
            '<?php endfor; ?>',
            '<?php while($1): ?>',
            '<?php endwhile; ?>',
            '<?php echo $1; ?>',
            '<?php echo htmlentities($1); ?>',
            '<?php $1; ?>'
        ];

        /**
         * Compile template file replacing the
         * alternative syntax in php code.
         *
         * @param string $buffer
         * @return string
         */
        protected function compile(string $buffer): string
        {
            return preg_replace($this->find, $this->replace, $buffer);
        }
    }