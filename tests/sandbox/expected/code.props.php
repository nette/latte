<?php
%A%
		echo 'props

';
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->prop($obj, 'prop')->prop) /* line %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->prop($this->global->sandbox->prop($obj, 'prop')->prop, 'prop')->prop) /* line %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->prop($this->global->sandbox->prop($obj, 'prop')->prop, $prop)->{$prop}) /* line %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->prop($this->global->sandbox->prop($obj, 'prop')->prop, 'prop')->{'prop'}) /* line %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->prop($obj, 'prop')->prop[$x]) /* line %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->prop($obj, 'prop')->prop[$x]) /* line %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->prop($this->global->sandbox->prop($obj, 'prop')->prop[$x], 'prop')->prop) /* line %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->prop($this->global->sandbox->prop($obj, 'prop')->prop['x'], $prop)->{$prop}) /* line %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->prop($this->global->sandbox->prop($obj, 'prop')->prop['x'], 'prop')->{'prop'}) /* line %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->prop($this->global->sandbox->prop($obj, 'prop')->prop['x']['y'], 'prop')->prop) /* line %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->callMethod($this->global->sandbox->prop($obj, 'prop')->prop['x'], 'method', [], false)) /* line %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->prop($obj, 'prop')->{'prop'}) /* line %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->prop($this->global->sandbox->prop($obj, 'prop')->{'prop'}, 'prop')->prop) /* line %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->prop($obj, $prop)->{$prop}) /* line %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->prop($obj, $prop)->{$prop}[$x]) /* line %d%:%d% */;
		echo '
-';
%A%
