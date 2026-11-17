<?php declare(strict_types=1);
%A%
		echo 'optional chaining

';
		echo ((is_object($ʟ_val = $this->global->sandbox->prop($obj, 'prop')?->prop) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos 3:1 */;
		echo "\n";
		echo ((is_object($ʟ_val = $this->global->sandbox->method($obj, 'bar')?->bar()) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos 4:1 */;
		echo '
-';
%A%
