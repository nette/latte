<?php
%A%
		echo 'consts

';
		echo LR\HtmlHelpers::escapeText(\Name\MyClass::CONST) /* line %d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($obj::CONST) /* line %d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($obj::CONST) /* line %d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->call($obj::CONST, [])) /* line %d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->call($obj::CONST[0], [])) /* line %d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->call(\CONST[0], [])) /* line %d% */;
		echo '
-';
%A%
