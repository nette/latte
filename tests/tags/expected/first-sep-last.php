<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{

	public function main(array $ʟ_args): void
	{
%A%
		echo "\n";
		foreach ($iterator = $ʟ_it = new Latte\Essential\CachingIterator($people, $ʟ_it ?? null) as $person) /* line 2:1 */ {
			echo '	';
			if ($iterator->isFirst()) /* line 3:2 */ {
				echo '(';
			}
			echo ' ';
			echo LR\Filters::escapeHtmlText($person) /* line 3:19 */;
			if (!$iterator->isLast()) /* line 3:28 */ {
				echo ', ';
			}
			echo ' ';
			if ($iterator->isLast()) /* line 3:42 */ {
				echo ')';
			}
			echo "\n";

		}
		$iterator = $ʟ_it = $ʟ_it->getParent();

		echo '

';
		foreach ($iterator = $ʟ_it = new Latte\Essential\CachingIterator($people, $ʟ_it ?? null) as $person) /* line 7:1 */ {
			echo '	';
			if ($iterator->isFirst()) /* line 8:2 */ {
				echo '(';
			} else /* line 8:10 */ {
				echo '[';
			}
			echo ' ';
			echo LR\Filters::escapeHtmlText($person) /* line 8:26 */;
			if (!$iterator->isLast()) /* line 8:35 */ {
				echo ', ';
			} else /* line 8:42 */ {
				echo ';';
			}
			echo ' ';
			if ($iterator->isLast()) /* line 8:56 */ {
				echo ')';
			} else /* line 8:63 */ {
				echo ']';
			}
			echo "\n";

		}
		$iterator = $ʟ_it = $ʟ_it->getParent();

		echo '

';
		foreach ($iterator = $ʟ_it = new Latte\Essential\CachingIterator($people, $ʟ_it ?? null) as $person) /* line 12:1 */ {
			echo '	';
			if ($iterator->isFirst(2)) /* line 13:2 */ {
				echo '(';
			}
			echo ' ';
			echo LR\Filters::escapeHtmlText($person) /* line 13:21 */;
			if (!$iterator->isLast(2)) /* line 13:30 */ {
				echo ', ';
			}
			echo ' ';
			if ($iterator->isLast(2)) /* line 13:46 */ {
				echo ')';
			}
			echo "\n";

		}
		$iterator = $ʟ_it = $ʟ_it->getParent();

		echo '

';
		foreach ($iterator = $ʟ_it = new Latte\Essential\CachingIterator($people, $ʟ_it ?? null) as $person) /* line 17:1 */ {
			echo '	';
			if ($iterator->isFirst(1)) /* line 18:2 */ {
				echo '(';
			}
			echo ' ';
			echo LR\Filters::escapeHtmlText($person) /* line 18:21 */;
			if (!$iterator->isLast(1)) /* line 18:30 */ {
				echo ', ';
			}
			echo ' ';
			if ($iterator->isLast(1)) /* line 18:46 */ {
				echo ')';
			}
			echo "\n";

		}
		$iterator = $ʟ_it = $ʟ_it->getParent();

		echo '

';
		foreach ($iterator = $ʟ_it = new Latte\Essential\CachingIterator($people, $ʟ_it ?? null) as $person) /* line 22:1 */ {
			if ($iterator->isFirst(0)) /* line 23:8 */ {
				echo '	<span>(</span>';
			}
			echo ' ';
			echo LR\Filters::escapeHtmlText($person) /* line 23:27 */;
			if (!$iterator->isLast()) /* line 23:42 */ {
				echo '<span>, </span>';
			}
			echo ' ';
			if ($iterator->isLast()) /* line 23:64 */ {
				echo '<span>)</span>
';
			}

		}
		$iterator = $ʟ_it = $ʟ_it->getParent();

		echo '

';
		foreach ($iterator = $ʟ_it = new Latte\Essential\CachingIterator($people, $ʟ_it ?? null) as $person) /* line 27:4 */ {
			echo '<p class="';
			if ($iterator->isFirst()) /* line 27:42 */ {
				echo '$person';
			}
			echo '"></p>
';

		}
		$iterator = $ʟ_it = $ʟ_it->getParent();
%A%
