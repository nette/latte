<?php
%A%
		echo 'static methods

';
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->call(['MyClass', 'method'], [])) /* line %d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->call(['Name\\MyClass', 'method'], [])) /* line %d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->call(['Name\\MyClass', 'method'], [])) /* line %d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->call(['Name\\MyClass', $method], [])) /* line %d% */;
		echo '
-';
%A%
