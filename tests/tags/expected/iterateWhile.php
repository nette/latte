<?php
%A%
		foreach ($iterator = $ʟ_it = new Latte\Essential\CachingIterator([0, 1, 2, 3], $ʟ_it ?? null) as $item) /* line 2 */ {
			echo '	pre ';
			echo LR\Filters::escapeHtmlText($item) /* line 3 */;
			echo "\n";
			do /* line 4 */ {
				if (!$iterator->hasNext() || !($item % 2)) {
					break;
				}
				$iterator->next();
				[, $item] = [$iterator->key(), $iterator->current()];
				echo '		inner ';
				echo LR\Filters::escapeHtmlText($item) /* line 5 */;
				echo "\n";

			}
			while (true);
			echo '	post ';
			echo LR\Filters::escapeHtmlText($item) /* line 7 */;
			echo "\n";

		}
		$iterator = $ʟ_it = $ʟ_it->getParent();

		echo '
---

';
		foreach ($iterator = $ʟ_it = new Latte\Essential\CachingIterator([0, 1, 2, 3], $ʟ_it ?? null) as $item) /* line 12 */ {
			echo '	pre ';
			echo LR\Filters::escapeHtmlText($item) /* line 13 */;
			echo "\n";
			do /* line 14 */ {
				echo '		inner ';
				echo LR\Filters::escapeHtmlText($item) /* line 15 */;
				echo "\n";

				if (!$iterator->hasNext() || !($item % 2)) {
					break;
				}
				$iterator->next();
				[, $item] = [$iterator->key(), $iterator->current()];
			}
			while (true);
			echo '	post ';
			echo LR\Filters::escapeHtmlText($item) /* line 17 */;
			echo "\n";

		}
		$iterator = $ʟ_it = $ʟ_it->getParent();

		echo '
---

';
		foreach ($iterator = $ʟ_it = new Latte\Essential\CachingIterator(['a' => [0], 'b' => [1], 'c' => [2]], $ʟ_it ?? null) as $key => [$i]) /* line 22 */ {
			echo '	pre ';
			echo LR\Filters::escapeHtmlText($key) /* line 23 */;
			echo ' ';
			echo LR\Filters::escapeHtmlText($i) /* line 23 */;
			echo "\n";
			do /* line 24 */ {
				echo '		inner ';
				echo LR\Filters::escapeHtmlText($key) /* line 25 */;
				echo ' ';
				echo LR\Filters::escapeHtmlText($i) /* line 25 */;
				echo "\n";

				if (!$iterator->hasNext() || !(true)) {
					break;
				}
				$iterator->next();
				[$key, [$i]] = [$iterator->key(), $iterator->current()];
			}
			while (true);
			echo '	post ';
			echo LR\Filters::escapeHtmlText($key) /* line 27 */;
			echo ' ';
			echo LR\Filters::escapeHtmlText($i) /* line 27 */;
			echo "\n";

		}
%A%
