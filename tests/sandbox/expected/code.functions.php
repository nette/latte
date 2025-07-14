<?php
%A%
		echo 'functions

';
		echo LR\HtmlHelpers::escapeText(func()) /* line %d% */;
		echo '
';
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->call('func', [])) /* line %d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->call('fu' . 'nc', [])) /* line %d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText(\func()) /* line %d% */;
		echo '
';
		echo LR\HtmlHelpers::escapeText(ns\func()) /* line %d% */;
		echo '
';
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->prop(func(), 'prop')->prop) /* line %d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->call(func(), [])) /* line %d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->call(func(...$this->global->sandbox->args($this->global->sandbox->prop($a, 'prop')->prop)), [func()])) /* line %d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->call(func()['x'], [])) /* line %d% */;
		echo '
-';
%A%
