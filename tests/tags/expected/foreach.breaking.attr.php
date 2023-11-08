<?php
%A%
		echo '
<ul title="foreach break">
';
		foreach ([0, 1, 2, 3] as $i) /* line 3 */ {
			echo '	<li>';
			try {
				echo LR\Filters::escapeHtmlText($i) /* line 3 */;
				if (true) /* line 3 */ break;
			} finally {
				echo '</li>';
			}
			echo "\n";

		}

		echo '</ul>

<ul title="foreach continue">
';
		foreach ([0, 1, 2, 3] as $i) /* line 7 */ {
			echo '	<li>';
			try {
				echo LR\Filters::escapeHtmlText($i) /* line 7 */;
				if (true) /* line 7 */ continue;
			} finally {
				echo '</li>';
			}
			echo "\n";

		}

		echo '</ul>

<ul title="foreach skip">
';
		foreach ($iterator = $ʟ_it = new Latte\Essential\CachingIterator([0, 1, 2, 3], $ʟ_it ?? null) as $i) /* line 11 */ {
			echo '	<li>';
			try {
				echo LR\Filters::escapeHtmlText($i) /* line 11 */;
				if (true) /* line 11 */ {
					$iterator->skipRound();
					continue;
				}
			} finally {
				echo '</li>';
			}
			echo "\n";

		}
		$iterator = $ʟ_it = $ʟ_it->getParent();

		echo '</ul>


<ul title="inner foreach break">
	<li>';
		foreach ([0, 1, 2, 3] as $i) /* line 16 */ {
			echo LR\Filters::escapeHtmlText($i) /* line 16 */;
			if (true) /* line 16 */ break;

		}

		echo '</li>
</ul>

<ul title="inner foreach continue">
	<li>';
		foreach ([0, 1, 2, 3] as $i) /* line 20 */ {
			echo LR\Filters::escapeHtmlText($i) /* line 20 */;
			if (true) /* line 20 */ continue;

		}

		echo '</li>
</ul>

<ul title="inner foreach skip">
	<li>';
		foreach ($iterator = $ʟ_it = new Latte\Essential\CachingIterator([0, 1, 2, 3], $ʟ_it ?? null) as $i) /* line 24 */ {
			echo LR\Filters::escapeHtmlText($i) /* line 24 */;
			if (true) /* line 24 */ {
				$iterator->skipRound();
				continue;
			}

		}
		$iterator = $ʟ_it = $ʟ_it->getParent();

		echo '</li>
</ul>
';
%A%
