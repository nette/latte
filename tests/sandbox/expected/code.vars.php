<?php
%A%
		echo 'vars

';
		echo LR\Filters::escapeHtmlText($var['x']) /* line %d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->prop($var['' . change(...$this->global->sandbox->args(10 + inner()))], 'prop')->prop) /* line %d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->callMethod($var[0 + 1], 'method', [], false)) /* line %d% */;
		echo '
-';
%A%
