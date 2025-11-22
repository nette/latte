<?php
%A%
		echo 'object methods

';
		echo LR\Filters::escapeHtmlText($this->global->sandbox->callMethod($obj, 'method', [], false)) /* pos %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->callMethod($obj, 'method', [], false)) /* pos %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->callMethod($obj, $method, [], false)) /* pos %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->call([$obj, 'method'], [])) /* pos %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->call($this->global->sandbox->callMethod($obj, 'method', [], false), [])) /* pos %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->prop($this->global->sandbox->callMethod($obj, 'method', [], false), 'prop')->prop) /* pos %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->prop($this->global->sandbox->call([$obj, 'method'], []), 'prop')->prop) /* pos %d%:%d% */;
		echo '
-';
%A%
