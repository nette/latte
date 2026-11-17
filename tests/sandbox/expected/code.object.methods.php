<?php declare(strict_types=1);
%A%
		echo 'object methods

';
		echo ((is_object($ʟ_val = $this->global->sandbox->method($obj, 'method')->method()) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos %d%:%d% */;
		echo "\n";
		echo ((is_object($ʟ_val = $this->global->sandbox->method($obj, 'method')->{'method'}()) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos %d%:%d% */;
		echo "\n";
		echo ((is_object($ʟ_val = $this->global->sandbox->method($obj, $ʟ_name0 = $method)->{$ʟ_name0}()) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos %d%:%d% */;
		echo "\n";
		echo ((is_object($ʟ_val = $this->global->sandbox->call([$obj, 'method'], [])) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos %d%:%d% */;
		echo "\n";
		echo ((is_object($ʟ_val = $this->global->sandbox->call($this->global->sandbox->method($obj, 'method')->method(), [])) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos %d%:%d% */;
		echo "\n";
		echo ((is_object($ʟ_val = $this->global->sandbox->prop($this->global->sandbox->method($obj, 'method')->method(), 'prop')->prop) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos %d%:%d% */;
		echo "\n";
		echo ((is_object($ʟ_val = $this->global->sandbox->prop($this->global->sandbox->call([$obj, 'method'], []), 'prop')->prop) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos %d%:%d% */;
		echo '
-';
%A%
