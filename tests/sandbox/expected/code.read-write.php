<?php
%A%
		echo 'read-write

';
		echo LR\Filters::escapeHtmlText($this->global->sandbox->prop($obj, 'bar')->bar++) /* line %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->prop($obj, 'static')::$static++) /* line %d%:%d% */;
		echo '
-';
%A%
