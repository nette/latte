<?php declare(strict_types=1);
%A%
		foreach ($iterator = $ʟ_it = new Latte\Essential\CachingIterator([0, 1, 2, 3], $ʟ_it ?? null) as $item) /* pos 2:1 */ {
			if ($item % 2) /* pos 3:2 */ continue;
			echo '	';
			echo ((is_object($ʟ_val = $iterator->counter) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos 4:2 */;
			echo '. item
';

		}
		$iterator = $ʟ_it = $ʟ_it->getParent();

		echo '
---

';
		foreach ($iterator = $ʟ_it = new Latte\Essential\CachingIterator([0, 1, 2, 3], $ʟ_it ?? null) as $item) /* pos 9:1 */ {
			if ($item % 2) /* pos 10:2 */ {
				$iterator->skipRound();
				continue;
			}
			echo '	';
			echo ((is_object($ʟ_val = $iterator->counter) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos 11:2 */;
			echo '. item
';

		}
		$iterator = $ʟ_it = $ʟ_it->getParent();

		echo '
---

';
		foreach ($iterator = $ʟ_it = new Latte\Essential\CachingIterator([0, 1, 2, 3], $ʟ_it ?? null) as $item) /* pos 16:1 */ {
			if ($item % 2) /* pos 17:2 */ break;
			echo '	';
			echo ((is_object($ʟ_val = $iterator->counter) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos 18:2 */;
			echo '. item
';

		}
		$iterator = $ʟ_it = $ʟ_it->getParent();
%A%
