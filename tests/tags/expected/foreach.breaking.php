<?php
%A%
		foreach ($iterator = $ʟ_it = new Latte\Essential\CachingIterator([0, 1, 2, 3], $ʟ_it ?? null) as $item) /* line 2 */ {
			if ($item % 2) /* line 3 */ continue;
			echo '	';
			echo LR\Filters::escapeHtmlText($iterator->counter) /* line 4 */;
			echo '. item
';

		}
		$iterator = $ʟ_it = $ʟ_it->getParent();

		echo '
---

';
		foreach ($iterator = $ʟ_it = new Latte\Essential\CachingIterator([0, 1, 2, 3], $ʟ_it ?? null) as $item) /* line 9 */ {
			if ($item % 2) /* line 10 */ {
				$iterator->skipRound();
				continue;
			}
			echo '	';
			echo LR\Filters::escapeHtmlText($iterator->counter) /* line 11 */;
			echo '. item
';

		}
		$iterator = $ʟ_it = $ʟ_it->getParent();

		echo '
---

';
		foreach ($iterator = $ʟ_it = new Latte\Essential\CachingIterator([0, 1, 2, 3], $ʟ_it ?? null) as $item) /* line 16 */ {
			if ($item % 2) /* line 17 */ break;
			echo '	';
			echo LR\Filters::escapeHtmlText($iterator->counter) /* line 18 */;
			echo '. item
';

		}
		$iterator = $ʟ_it = $ʟ_it->getParent();
%A%
