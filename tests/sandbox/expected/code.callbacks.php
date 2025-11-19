<?php
%A%
		echo 'callbacks

';
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->call($var, [])) /* pos %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->call($var, [])) /* pos %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->call(['a', 'b'], [])) /* pos %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->call(['trim'][0], [])) /* pos %d%:%d% */;
		echo '
-';
%A%
