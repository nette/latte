<?php
%A%
		foreach ($iterator = $ʟ_it = new Latte\Essential\CachingIterator([0, 1, 2, 3], $ʟ_it ?? null) as $item) /* line 2:1 */ {
			if ($item % 2) /* line 3:2 */ continue;
			echo '	';
			echo LR\HtmlHelpers::escapeText($iterator->counter) /* line 4:2 */;
			echo '. item
';

		}
		$iterator = $ʟ_it = $ʟ_it->getParent();

		echo '
---

';
		foreach ($iterator = $ʟ_it = new Latte\Essential\CachingIterator([0, 1, 2, 3], $ʟ_it ?? null) as $item) /* line 9:1 */ {
			if ($item % 2) /* line 10:2 */ {
				$iterator->skipRound();
				continue;
			}
			echo '	';
			echo LR\HtmlHelpers::escapeText($iterator->counter) /* line 11:2 */;
			echo '. item
';

		}
		$iterator = $ʟ_it = $ʟ_it->getParent();

		echo '
---

';
		foreach ($iterator = $ʟ_it = new Latte\Essential\CachingIterator([0, 1, 2, 3], $ʟ_it ?? null) as $item) /* line 16:1 */ {
			if ($item % 2) /* line 17:2 */ break;
			echo '	';
			echo LR\HtmlHelpers::escapeText($iterator->counter) /* line 18:2 */;
			echo '. item
';

		}
		$iterator = $ʟ_it = $ʟ_it->getParent();
%A%
