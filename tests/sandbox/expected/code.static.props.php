<?php
%A%
		echo 'static props

';
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->prop('Name\\MyClass', 'prop')::$prop) /* pos %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->prop($this->global->sandbox->prop('Name\\MyClass', 'prop')::$prop, 'x')->x) /* pos %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->prop('Name\\MyClass', 'prop')::$prop[1]) /* pos %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->prop($this->global->sandbox->prop('Name\\MyClass', 'prop')::$prop[1], 'x')->x) /* pos %d%:%d% */;
		echo '
-';
%A%
