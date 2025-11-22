<?php
%A%
		echo 'consts

';
		echo LR\Filters::escapeHtmlText(\Name\MyClass::CONST) /* pos %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($obj::CONST) /* pos %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($obj::CONST) /* pos %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->call($obj::CONST, [])) /* pos %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->call($obj::CONST[0], [])) /* pos %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->call(\CONST[0], [])) /* pos %d%:%d% */;
		echo '
-';
%A%
