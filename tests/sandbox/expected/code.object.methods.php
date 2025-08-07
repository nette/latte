<?php
%A%
		echo 'object methods

';
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->callMethod($obj, 'method', [], false)) /* line %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->callMethod($obj, 'method', [], false)) /* line %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->callMethod($obj, $method, [], false)) /* line %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->call([$obj, 'method'], [])) /* line %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->call($this->global->sandbox->callMethod($obj, 'method', [], false), [])) /* line %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->prop($this->global->sandbox->callMethod($obj, 'method', [], false), 'prop')->prop) /* line %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->prop($this->global->sandbox->call([$obj, 'method'], []), 'prop')->prop) /* line %d%:%d% */;
		echo '
-';
%A%
