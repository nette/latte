<?php
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
			echo LR\HtmlHelpers::escapeText($iterator->counter) /* pos 9:2 */;
			echo "\n";

		}
		$iterator = $ʟ_it = $ʟ_it->getParent();

		echo LR\HtmlHelpers::escapeText($iterator === null ? 'is null' : null) /* pos 11:1 */;
		echo '

---

';
		foreach ([['a', null, null]] as [$a, , [$b, [$c]]]) /* pos 15:1 */ {
		}
%A%
