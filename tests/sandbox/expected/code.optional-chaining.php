<?php
%A%
		echo 'optional chaining

';
		echo LR\Filters::escapeHtmlText($this->global->sandbox->prop($obj, 'prop')?->prop) /* line 3 */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->callMethod($obj, 'bar', [], true)) /* line 4 */;
		echo '
-';
%A%
