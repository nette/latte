<?php
%A%
		echo 'read-write

';
		echo LR\Filters::escapeHtmlText($this->global->sandbox->prop($obj, 'bar')->bar++) /* pos %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->prop($obj, 'static')::$static++) /* pos %d%:%d% */;
		echo '
-';
%A%
