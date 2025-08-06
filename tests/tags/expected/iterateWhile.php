<?php
%A%
		foreach ($iterator = $ʟ_it = new Latte\Essential\CachingIterator([0, 1, 2, 3], $ʟ_it ?? null) as $item) /* line 2:1 */ {
			echo '	pre ';
			echo LR\Filters::escapeHtmlText($item) /* line 3:6 */;
			echo "\n";
			do /* line 4:2 */ {
				if (!$iterator->hasNext() || !($item % 2)) {
					break;
				}
				$iterator->next();
				[, $item] = [$iterator->key(), $iterator->current()];
				echo '		inner ';
				echo LR\Filters::escapeHtmlText($item) /* line 5:9 */;
				echo "\n";

			}
			while (true);
			echo '	post ';
			echo LR\Filters::escapeHtmlText($item) /* line 7:7 */;
			echo "\n";

		}
		$iterator = $ʟ_it = $ʟ_it->getParent();

		echo '
---

';
		foreach ($iterator = $ʟ_it = new Latte\Essential\CachingIterator([0, 1, 2, 3], $ʟ_it ?? null) as $item) /* line 12:1 */ {
			echo '	pre ';
			echo LR\Filters::escapeHtmlText($item) /* line 13:6 */;
			echo "\n";
			do /* line 14:2 */ {
				echo '		inner ';
				echo LR\Filters::escapeHtmlText($item) /* line 15:9 */;
				echo "\n";

				if (!$iterator->hasNext() || !($item % 2)) {
					break;
				}
				$iterator->next();
				[, $item] = [$iterator->key(), $iterator->current()];
			}
			while (true);
			echo '	post ';
			echo LR\Filters::escapeHtmlText($item) /* line 17:7 */;
			echo "\n";

		}
		$iterator = $ʟ_it = $ʟ_it->getParent();

		echo '
---

';
		foreach ($iterator = $ʟ_it = new Latte\Essential\CachingIterator(['a' => [0], 'b' => [1], 'c' => [2]], $ʟ_it ?? null) as $key => [$i]) /* line 22:1 */ {
			echo '	pre ';
			echo LR\Filters::escapeHtmlText($key) /* line 23:6 */;
			echo ' ';
			echo LR\Filters::escapeHtmlText($i) /* line 23:13 */;
			echo "\n";
			do /* line 24:2 */ {
				echo '		inner ';
				echo LR\Filters::escapeHtmlText($key) /* line 25:9 */;
				echo ' ';
				echo LR\Filters::escapeHtmlText($i) /* line 25:16 */;
				echo "\n";

				if (!$iterator->hasNext() || !(true)) {
					break;
				}
				$iterator->next();
				[$key, [$i]] = [$iterator->key(), $iterator->current()];
			}
			while (true);
			echo '	post ';
			echo LR\Filters::escapeHtmlText($key) /* line 27:7 */;
			echo ' ';
			echo LR\Filters::escapeHtmlText($i) /* line 27:14 */;
			echo "\n";

		}
%A%
