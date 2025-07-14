<?php
%A%
		echo 'read-write

';
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->prop($obj, 'bar')->bar++) /* line %d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->prop($obj, 'static')::$static++) /* line %d% */;
		echo '
-';
%A%
