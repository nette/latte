<?php
%A%
		foreach (['a', 'b'] as $item) /* line 2 */ {
			echo '	item
';

		}

		echo '
---

';
		foreach ($iterator = $ʟ_it = new Latte\Essential\CachingIterator(['a', 'b'], $ʟ_it ?? null) as $item) /* line 8 */ {
			echo '	';
			echo LR\Filters::escapeHtmlText($iterator->counter) /* line 9 */;
			echo "\n";

		}
		$iterator = $ʟ_it = $ʟ_it->getParent();

		echo LR\Filters::escapeHtmlText($iterator === null ? 'is null' : null) /* line 11 */;
		echo '

---

';
		foreach (['a', 'b'] as [$a, , [$b, [$c]]]) /* line 15 */ {
		}
%A%
