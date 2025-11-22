<?php
%A%
		echo 'firstclass callable

';
		echo LR\Filters::escapeHtmlText($this->global->sandbox->closure('trim')) /* pos %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->closure([$obj, 'foo'])) /* pos %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->closure([$obj, 'foo'])) /* pos %d%:%d% */;
		echo '
-';
%A%
