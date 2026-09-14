<?php declare(strict_types=1);
%A%
		echo 'object methods

';
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->method($obj, 'method')->method()) /* pos %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->method($obj, 'method')->{'method'}()) /* pos %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->method($obj, $ʟ_name0 = $method)->{$ʟ_name0}()) /* pos %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->call([$obj, 'method'], [])) /* pos %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->call($this->global->sandbox->method($obj, 'method')->method(), [])) /* pos %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->prop($this->global->sandbox->method($obj, 'method')->method(), 'prop')->prop) /* pos %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->prop($this->global->sandbox->call([$obj, 'method'], []), 'prop')->prop) /* pos %d%:%d% */;
		echo '
-';
%A%
