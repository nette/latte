<?php
%A%
		echo 'consts

';
		echo LR\HtmlHelpers::escapeText(\Name\MyClass::CONST) /* pos %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($obj::CONST) /* pos %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($obj::CONST) /* pos %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->call($obj::CONST, [])) /* pos %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->call($obj::CONST[0], [])) /* pos %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->call(\CONST[0], [])) /* pos %d%:%d% */;
		echo '
-';
%A%
