<?php
%A%
		echo 'callbacks

';
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->call($var, [])) /* line %d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->call($var, [])) /* line %d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->call(['a', 'b'], [])) /* line %d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->call(['trim'][0], [])) /* line %d% */;
		echo '
-';
%A%
