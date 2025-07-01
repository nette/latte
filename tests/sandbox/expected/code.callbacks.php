<?php
%A%
		echo 'callbacks

';
		echo LR\Filters::escapeHtmlText($this->global->sandbox->call($var, [])) /* line %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->call($var, [])) /* line %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->call(['a', 'b'], [])) /* line %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->call(['trim'][0], [])) /* line %d%:%d% */;
		echo '
-';
%A%
