<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{

	public function main(array $ʟ_args): void
	{
%A%
		echo "\n";
		foreach ($iterator = $ʟ_it = new Latte\Essential\CachingIterator($people, $ʟ_it ?? null) as $person) /* pos 2:1 */ {
			echo '	';
			if ($iterator->isFirst()) /* pos 3:2 */ {
				echo '(';
			}
			echo ' ';
			echo LR\Filters::escapeHtmlText($person) /* pos 3:19 */;
			if (!$iterator->isLast()) /* pos 3:28 */ {
				echo ', ';
			}
			echo ' ';
			if ($iterator->isLast()) /* pos 3:42 */ {
				echo ')';
			}
			echo "\n";

		}
		$iterator = $ʟ_it = $ʟ_it->getParent();

		echo '

';
		foreach ($iterator = $ʟ_it = new Latte\Essential\CachingIterator($people, $ʟ_it ?? null) as $person) /* pos 7:1 */ {
			echo '	';
			if ($iterator->isFirst()) /* pos 8:2 */ {
				echo '(';
			} else /* pos 8:10 */ {
				echo '[';
			}
			echo ' ';
			echo LR\Filters::escapeHtmlText($person) /* pos 8:26 */;
			if (!$iterator->isLast()) /* pos 8:35 */ {
				echo ', ';
			} else /* pos 8:42 */ {
				echo ';';
			}
			echo ' ';
			if ($iterator->isLast()) /* pos 8:56 */ {
				echo ')';
			} else /* pos 8:63 */ {
				echo ']';
			}
			echo "\n";

		}
		$iterator = $ʟ_it = $ʟ_it->getParent();

		echo '

';
		foreach ($iterator = $ʟ_it = new Latte\Essential\CachingIterator($people, $ʟ_it ?? null) as $person) /* pos 12:1 */ {
			echo '	';
			if ($iterator->isFirst(2)) /* pos 13:2 */ {
				echo '(';
			}
			echo ' ';
			echo LR\Filters::escapeHtmlText($person) /* pos 13:21 */;
			if (!$iterator->isLast(2)) /* pos 13:30 */ {
				echo ', ';
			}
			echo ' ';
			if ($iterator->isLast(2)) /* pos 13:46 */ {
				echo ')';
			}
			echo "\n";

		}
		$iterator = $ʟ_it = $ʟ_it->getParent();

		echo '

';
		foreach ($iterator = $ʟ_it = new Latte\Essential\CachingIterator($people, $ʟ_it ?? null) as $person) /* pos 17:1 */ {
			echo '	';
			if ($iterator->isFirst(1)) /* pos 18:2 */ {
				echo '(';
			}
			echo ' ';
			echo LR\Filters::escapeHtmlText($person) /* pos 18:21 */;
			if (!$iterator->isLast(1)) /* pos 18:30 */ {
				echo ', ';
			}
			echo ' ';
			if ($iterator->isLast(1)) /* pos 18:46 */ {
				echo ')';
			}
			echo "\n";

		}
		$iterator = $ʟ_it = $ʟ_it->getParent();

		echo '

';
		foreach ($iterator = $ʟ_it = new Latte\Essential\CachingIterator($people, $ʟ_it ?? null) as $person) /* pos 22:1 */ {
			if ($iterator->isFirst(0)) /* pos 23:8 */ {
				echo '	<span>(</span>';
			}
			echo ' ';
			echo LR\Filters::escapeHtmlText($person) /* pos 23:27 */;
			if (!$iterator->isLast()) /* pos 23:42 */ {
				echo '<span>, </span>';
			}
			echo ' ';
			if ($iterator->isLast()) /* pos 23:64 */ {
				echo '<span>)</span>
';
			}

		}
		$iterator = $ʟ_it = $ʟ_it->getParent();

		echo '

';
		foreach ($iterator = $ʟ_it = new Latte\Essential\CachingIterator($people, $ʟ_it ?? null) as $person) /* pos 27:4 */ {
			echo '<p class="';
			if ($iterator->isFirst()) /* pos 27:42 */ {
				echo '$person';
			}
			echo '"></p>
';

		}
		$iterator = $ʟ_it = $ʟ_it->getParent();
%A%
