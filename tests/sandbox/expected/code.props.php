<?php
%A%
		echo 'props

';
		echo LR\Filters::escapeHtmlText($this->global->sandbox->prop($obj, 'prop')->prop) /* pos %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->prop($this->global->sandbox->prop($obj, 'prop')->prop, 'prop')->prop) /* pos %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->prop($this->global->sandbox->prop($obj, 'prop')->prop, $prop)->{$prop}) /* pos %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->prop($this->global->sandbox->prop($obj, 'prop')->prop, 'prop')->{'prop'}) /* pos %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->prop($obj, 'prop')->prop[$x]) /* pos %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->prop($obj, 'prop')->prop[$x]) /* pos %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->prop($this->global->sandbox->prop($obj, 'prop')->prop[$x], 'prop')->prop) /* pos %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->prop($this->global->sandbox->prop($obj, 'prop')->prop['x'], $prop)->{$prop}) /* pos %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->prop($this->global->sandbox->prop($obj, 'prop')->prop['x'], 'prop')->{'prop'}) /* pos %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->prop($this->global->sandbox->prop($obj, 'prop')->prop['x']['y'], 'prop')->prop) /* pos %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->callMethod($this->global->sandbox->prop($obj, 'prop')->prop['x'], 'method', [], false)) /* pos %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->prop($obj, 'prop')->{'prop'}) /* pos %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->prop($this->global->sandbox->prop($obj, 'prop')->{'prop'}, 'prop')->prop) /* pos %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->prop($obj, $prop)->{$prop}) /* pos %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->prop($obj, $prop)->{$prop}[$x]) /* pos %d%:%d% */;
		echo '
-';
%A%
