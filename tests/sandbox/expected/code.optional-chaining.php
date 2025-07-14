<?php
%A%
		echo 'optional chaining

';
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->prop($obj, 'prop')?->prop) /* line %d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->prop($obj ?? null, 'prop')?->prop) /* line %d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->callMethod($obj, 'bar', [], true)) /* line %d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->callMethod($obj ?? null, 'bar', [], true)) /* line %d% */;
		echo '
-';
%A%
