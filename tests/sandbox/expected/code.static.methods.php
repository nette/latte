<?php
%A%
		echo 'static methods

';
		echo LR\Filters::escapeHtmlText($this->global->sandbox->call(['MyClass', 'method'], [])) /* line %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->call(['Name\\MyClass', 'method'], [])) /* line %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->call(['Name\\MyClass', 'method'], [])) /* line %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->call(['Name\\MyClass', $method], [])) /* line %d%:%d% */;
		echo '
-';
%A%
