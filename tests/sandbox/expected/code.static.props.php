<?php
%A%
		echo 'static props

';
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->prop('Name\\MyClass', 'prop')::$prop) /* line %d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->prop($this->global->sandbox->prop('Name\\MyClass', 'prop')::$prop, 'x')->x) /* line %d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->prop('Name\\MyClass', 'prop')::$prop[1]) /* line %d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->prop($this->global->sandbox->prop('Name\\MyClass', 'prop')::$prop[1], 'x')->x) /* line %d% */;
		echo '
-';
%A%
