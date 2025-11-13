<?php
%A%
		echo 'vars

';
		echo LR\HtmlHelpers::escapeText($var['x']) /* line %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->prop($var['' . change(...$this->global->sandbox->args(10 + inner()))], 'prop')->prop) /* line %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->callMethod($var[0 + 1], 'method', [], false)) /* line %d%:%d% */;
		echo '
-';
%A%
