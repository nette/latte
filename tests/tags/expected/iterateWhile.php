<?php declare(strict_types=1);
%A%
		foreach ($iterator = $ʟ_it = new Latte\Essential\CachingIterator([0, 1, 2, 3], $ʟ_it ?? null) as $item) /* pos 2:1 */ {
			echo '	pre ';
			echo ((is_object($ʟ_val = $item) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos 3:6 */;
			echo "\n";
			do /* pos 4:2 */ {
				if (!$iterator->hasNext() || !($item % 2)) {
					break;
				}
				$iterator->next();
				[, $item] = [$iterator->key(), $iterator->current()];
				echo '		inner ';
				echo ((is_object($ʟ_val = $item) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos 5:9 */;
				echo "\n";

			}
			while (true);
			echo '	post ';
			echo ((is_object($ʟ_val = $item) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos 7:7 */;
			echo "\n";

		}
		$iterator = $ʟ_it = $ʟ_it->getParent();

		echo '
---

';
		foreach ($iterator = $ʟ_it = new Latte\Essential\CachingIterator([0, 1, 2, 3], $ʟ_it ?? null) as $item) /* pos 12:1 */ {
			echo '	pre ';
			echo ((is_object($ʟ_val = $item) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos 13:6 */;
			echo "\n";
			do /* pos 14:2 */ {
				echo '		inner ';
				echo ((is_object($ʟ_val = $item) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos 15:9 */;
				echo "\n";

				if (!$iterator->hasNext() || !($item % 2)) {
					break;
				}
				$iterator->next();
				[, $item] = [$iterator->key(), $iterator->current()];
			}
			while (true);
			echo '	post ';
			echo ((is_object($ʟ_val = $item) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos 17:7 */;
			echo "\n";

		}
		$iterator = $ʟ_it = $ʟ_it->getParent();

		echo '
---

';
		foreach ($iterator = $ʟ_it = new Latte\Essential\CachingIterator(['a' => [0], 'b' => [1], 'c' => [2]], $ʟ_it ?? null) as $key => [$i]) /* pos 22:1 */ {
			echo '	pre ';
			echo ((is_object($ʟ_val = $key) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos 23:6 */;
			echo ' ';
			echo ((is_object($ʟ_val = $i) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos 23:13 */;
			echo "\n";
			do /* pos 24:2 */ {
				echo '		inner ';
				echo ((is_object($ʟ_val = $key) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos 25:9 */;
				echo ' ';
				echo ((is_object($ʟ_val = $i) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos 25:16 */;
				echo "\n";

				if (!$iterator->hasNext() || !(true)) {
					break;
				}
				$iterator->next();
				[$key, [$i]] = [$iterator->key(), $iterator->current()];
			}
			while (true);
			echo '	post ';
			echo ((is_object($ʟ_val = $key) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos 27:7 */;
			echo ' ';
			echo ((is_object($ʟ_val = $i) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos 27:14 */;
			echo "\n";

		}
%A%
