<?php
%A%
		echo 'object methods

';
		echo LR\Filters::escapeHtmlText($this->global->sandbox->callMethod($obj, 'method', [], false)) /* line %d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->callMethod($obj, 'method', [], false)) /* line %d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->callMethod($obj, $method, [], false)) /* line %d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->call([$obj, 'method'], [])) /* line %d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->call($this->global->sandbox->callMethod($obj, 'method', [], false), [])) /* line %d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->prop($this->global->sandbox->callMethod($obj, 'method', [], false), 'prop')->prop) /* line %d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->prop($this->global->sandbox->call([$obj, 'method'], []), 'prop')->prop) /* line %d% */;
		echo '
-';
%A%
