<?php
%A%
		foreach (['a', 'b'] as $item) /* line 2:1 */ {
			echo '	item
';

		}

		echo '
---

';
		foreach ($iterator = $ʟ_it = new Latte\Essential\CachingIterator(['a', 'b'], $ʟ_it ?? null) as $item) /* line 8:1 */ {
			echo '	';
			echo LR\Filters::escapeHtmlText($iterator->counter) /* line 9:2 */;
			echo "\n";

		}
		$iterator = $ʟ_it = $ʟ_it->getParent();

		echo LR\Filters::escapeHtmlText($iterator === null ? 'is null' : null) /* line 11:1 */;
		echo '

---

';
		foreach (['a', 'b'] as [$a, , [$b, [$c]]]) /* line 15:1 */ {
		}
%A%
