<?php declare(strict_types=1);
%A%
		echo 'props

';
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->prop($obj, 'prop')->prop) /* pos %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->prop($this->global->sandbox->prop($obj, 'prop')->prop, 'prop')->prop) /* pos %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->prop($this->global->sandbox->prop($obj, 'prop')->prop, $ʟ_name0 = $prop)->{$ʟ_name0}) /* pos %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->prop($this->global->sandbox->prop($obj, 'prop')->prop, 'prop')->{'prop'}) /* pos %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->prop($obj, 'prop')->prop[$x]) /* pos %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->prop($obj, 'prop')->prop[$x]) /* pos %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->prop($this->global->sandbox->prop($obj, 'prop')->prop[$x], 'prop')->prop) /* pos %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->prop($this->global->sandbox->prop($obj, 'prop')->prop['x'], $ʟ_name1 = $prop)->{$ʟ_name1}) /* pos %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->prop($this->global->sandbox->prop($obj, 'prop')->prop['x'], 'prop')->{'prop'}) /* pos %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->prop($this->global->sandbox->prop($obj, 'prop')->prop['x']['y'], 'prop')->prop) /* pos %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->method($this->global->sandbox->prop($obj, 'prop')->prop['x'], 'method')->method()) /* pos %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->prop($obj, 'prop')->{'prop'}) /* pos %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->prop($this->global->sandbox->prop($obj, 'prop')->{'prop'}, 'prop')->prop) /* pos %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->prop($obj, $ʟ_name2 = $prop)->{$ʟ_name2}) /* pos %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->prop($obj, $ʟ_name3 = $prop)->{$ʟ_name3}[$x]) /* pos %d%:%d% */;
		echo '
-';
%A%
