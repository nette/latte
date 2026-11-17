<?php declare(strict_types=1);
%A%
		echo 'consts

';
		echo ((is_object($ʟ_val = \Name\MyClass::CONST) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos %d%:%d% */;
		echo "\n";
		echo ((is_object($ʟ_val = $obj::CONST) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos %d%:%d% */;
		echo "\n";
		echo ((is_object($ʟ_val = $obj::CONST) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos %d%:%d% */;
		echo "\n";
		echo ((is_object($ʟ_val = $this->global->sandbox->call($obj::CONST, [])) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos %d%:%d% */;
		echo "\n";
		echo ((is_object($ʟ_val = $this->global->sandbox->call($obj::CONST[0], [])) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos %d%:%d% */;
		echo "\n";
		echo ((is_object($ʟ_val = $this->global->sandbox->call(\CONST[0], [])) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos %d%:%d% */;
		echo '
-';
%A%
