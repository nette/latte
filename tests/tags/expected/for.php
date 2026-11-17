<?php declare(strict_types=1);
%A%
		for ($i = 0;
		$i < 10;
		$i++) /* pos 2:1 */ {
			echo '	';
			echo ((is_object($ʟ_val = $i) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos 3:2 */;
			echo "\n";

		}
		echo '

';
		for (;
		;
		) /* pos 7:1 */ {
			echo '	';
			echo ((is_object($ʟ_val = $i) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos 8:2 */;
			echo "\n";

		}
		echo '

';
		for ($i = 0, $a = 1;
		$i < 10;
		$i++, $a++) /* pos 12:1 */ {
			echo '	';
			echo ((is_object($ʟ_val = $i) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos 13:2 */;
			echo "\n";

		}
		echo '

';
		for ($i = 0;
		$i < 10;
		$i++) /* pos 17:1 */ {
			if (true) /* pos 18:2 */ break;
			if (true) /* pos 19:2 */ continue;
			echo '	';
			echo ((is_object($ʟ_val = $i) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos 20:2 */;
			echo "\n";

		}
%A%
