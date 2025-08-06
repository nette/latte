<?php
%A%
		echo 'static props

';
		echo LR\Filters::escapeHtmlText($this->global->sandbox->prop('Name\\MyClass', 'prop')::$prop) /* line %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->prop($this->global->sandbox->prop('Name\\MyClass', 'prop')::$prop, 'x')->x) /* line %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->prop('Name\\MyClass', 'prop')::$prop[1]) /* line %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->prop($this->global->sandbox->prop('Name\\MyClass', 'prop')::$prop[1], 'x')->x) /* line %d%:%d% */;
		echo '
-';
%A%
