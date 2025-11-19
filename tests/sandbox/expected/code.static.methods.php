<?php
%A%
		echo 'static methods

';
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->call(['MyClass', 'method'], [])) /* pos %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->call(['Name\\MyClass', 'method'], [])) /* pos %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->call(['Name\\MyClass', 'method'], [])) /* pos %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->call(['Name\\MyClass', $method], [])) /* pos %d%:%d% */;
		echo '
-';
%A%
