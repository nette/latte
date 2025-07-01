%A%
		foreach ($iterator = $ʟ_it = new Latte\Essential\CachingIterator([1, 2, 3], $ʟ_it ?? null) as $foo) /* line 2:1 */ {
			echo '	<b';
			echo ($ʟ_tmp = array_filter([$iterator->even ? 'even' : null])) ? ' class="' . LR\Filters::escapeHtmlAttr(implode(" ", array_unique($ʟ_tmp))) . '"' : "" /* line 3:5 */;
			echo '>item</b>
';

		}
		$iterator = $ʟ_it = $ʟ_it->getParent();

		echo "\n";
		foreach ($iterator = $ʟ_it = new Latte\Essential\CachingIterator([1, 2, 3], $ʟ_it ?? null) as $foo) /* line 6:4 */ {
			echo '<p';
			echo ($ʟ_tmp = array_filter([$iterator->even ? 'even' : null])) ? ' class="' . LR\Filters::escapeHtmlAttr(implode(" ", array_unique($ʟ_tmp))) . '"' : "" /* line 6:34 */;
			echo '>';
			echo LR\Filters::escapeHtmlText($foo) /* line 6:67 */;
			echo '</p>
';

		}
		$iterator = $ʟ_it = $ʟ_it->getParent();

		echo '
<p';
		echo ($ʟ_tmp = array_filter(['foo', false ? 'first' : null, 'odd', true ? 'foo' : 'bar'])) ? ' class="' . LR\Filters::escapeHtmlAttr(implode(" ", array_unique($ʟ_tmp))) . '"' : "" /* line 8:4 */;
		echo '>n:class</p>

<p';
		echo ($ʟ_tmp = array_filter([false ? 'first' : null])) ? ' class="' . LR\Filters::escapeHtmlAttr(implode(" ", array_unique($ʟ_tmp))) . '"' : "" /* line 10:4 */;
		echo '>n:class empty</p>

<p';
		echo ($ʟ_tmp = array_filter([true ? 'bem--modifier' : null])) ? ' class="' . LR\Filters::escapeHtmlAttr(implode(" ", array_unique($ʟ_tmp))) . '"' : "" /* line 12:4 */;
		echo '>n:class with BEM</p>
';
%A%
