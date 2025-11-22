<?php
%A%
		echo 'functions

';
		echo LR\Filters::escapeHtmlText(func()) /* pos %d%:%d% */;
		echo '
';
		echo LR\Filters::escapeHtmlText($this->global->sandbox->call('func', [])) /* pos %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->call('fu' . 'nc', [])) /* pos %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText(\func()) /* pos %d%:%d% */;
		echo '
';
		echo LR\Filters::escapeHtmlText(ns\func()) /* pos %d%:%d% */;
		echo '
';
		echo LR\Filters::escapeHtmlText($this->global->sandbox->prop(func(), 'prop')->prop) /* pos %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->call(func(), [])) /* pos %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->call(func(...$this->global->sandbox->args($this->global->sandbox->prop($a, 'prop')->prop)), [func()])) /* pos %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->call(func()['x'], [])) /* pos %d%:%d% */;
		echo '
-';
%A%
