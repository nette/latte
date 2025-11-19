<?php
%A%
		echo 'read-write

';
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->prop($obj, 'bar')->bar++) /* pos %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->prop($obj, 'static')::$static++) /* pos %d%:%d% */;
		echo '
-';
%A%
