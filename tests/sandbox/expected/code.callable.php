<?php
%A%
		echo 'firstclass callable

';
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->closure('trim')) /* line %d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->closure([$obj, 'foo'])) /* line %d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->closure([$obj, 'foo'])) /* line %d% */;
		echo '
-';
%A%
