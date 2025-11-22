<?php
%A%
		echo 'optional chaining

';
		echo LR\Filters::escapeHtmlText($this->global->sandbox->prop($obj, 'prop')?->prop) /* pos 3:1 */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->callMethod($obj, 'bar', [], true)) /* pos 4:1 */;
		echo '
-';
%A%
