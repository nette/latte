<?php declare(strict_types=1);
%A%
		echo 'firstclass callable

';
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->closure('trim')) /* pos %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->closure([$obj, 'foo'])) /* pos %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->closure([$obj, 'foo'])) /* pos %d%:%d% */;
		echo '
-';
%A%
