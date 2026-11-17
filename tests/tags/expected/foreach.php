<?php declare(strict_types=1);
%A%
		foreach (['a', 'b'] as $item) /* pos 2:1 */ {
			echo '	item
';

		}

		echo '
---

';
		foreach ($iterator = $ʟ_it = new Latte\Essential\CachingIterator(['a', 'b'], $ʟ_it ?? null) as $item) /* pos 8:1 */ {
			echo '	';
			echo ((is_object($ʟ_val = $iterator->counter) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos 9:2 */;
			echo "\n";

		}
		$iterator = $ʟ_it = $ʟ_it->getParent();

		echo ((is_object($ʟ_val = $iterator === null ? 'is null' : null) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos 11:1 */;
		echo '

---

';
		foreach ([['a', null, null]] as [$a, , [$b, [$c]]]) /* pos 15:1 */ {
		}
%A%
