<?php declare(strict_types=1);
%A%
		echo 'static props

';
		echo ((is_object($ʟ_val = $this->global->sandbox->prop('Name\\MyClass', 'prop')::$prop) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos %d%:%d% */;
		echo "\n";
		echo ((is_object($ʟ_val = $this->global->sandbox->prop($this->global->sandbox->prop('Name\\MyClass', 'prop')::$prop, 'x')->x) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos %d%:%d% */;
		echo "\n";
		echo ((is_object($ʟ_val = $this->global->sandbox->prop('Name\\MyClass', 'prop')::$prop[1]) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos %d%:%d% */;
		echo "\n";
		echo ((is_object($ʟ_val = $this->global->sandbox->prop($this->global->sandbox->prop('Name\\MyClass', 'prop')::$prop[1], 'x')->x) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos %d%:%d% */;
		echo '
-';
%A%
