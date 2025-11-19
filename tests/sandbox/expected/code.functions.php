<?php
%A%
		echo 'functions

';
		echo LR\HtmlHelpers::escapeText(func()) /* pos %d%:%d% */;
		echo '
';
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->call('func', [])) /* pos %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->call('fu' . 'nc', [])) /* pos %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText(\func()) /* pos %d%:%d% */;
		echo '
';
		echo LR\HtmlHelpers::escapeText(ns\func()) /* pos %d%:%d% */;
		echo '
';
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->prop(func(), 'prop')->prop) /* pos %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->call(func(), [])) /* pos %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->call(func(...$this->global->sandbox->args($this->global->sandbox->prop($a, 'prop')->prop)), [func()])) /* pos %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($this->global->sandbox->call(func()['x'], [])) /* pos %d%:%d% */;
		echo '
-';
%A%
