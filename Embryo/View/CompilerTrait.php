<?php 

    namespace Embryo\View;

    trait CompilerTrait
    {
        private $find = [
            '/@include\((.*)\)/m',
            '/@if\((.*)\)/m',
            '/@else/m',
            '/@elseif\((.*)\)/m',
            '/@endif/m',
            '/@foreach\((.*)\)/m',
            '/@endforeach/m',
            '/@for\((.*)\)/m',
            '/@endfor/m',
            '/@while\((.*)\)/m',
            '/@endwhile/m',
            '/{{{\s([\$a-zA-Z_0-9\s:?]+)\s}}}/m',
            '/{{\s([\$a-zA-Z_0-9\s:?]+)\s}}/m',
            '/@php\s(.*)/m'
        ];

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

        protected function compile($buffer)
        {
            return preg_replace($this->find, $this->replace, $buffer);
        }
    }