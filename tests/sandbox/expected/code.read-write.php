<?php declare(strict_types=1);
%A%
		echo 'read-write

';
		echo ((is_object($ʟ_val = $this->global->sandbox->prop($obj, 'bar')->bar++) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos %d%:%d% */;
		echo "\n";
		echo ((is_object($ʟ_val = $this->global->sandbox->prop($obj, 'static')::$static++) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos %d%:%d% */;
		echo '
-';
%A%
