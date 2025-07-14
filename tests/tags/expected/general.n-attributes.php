<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{
	public const Source = '%a%.latte';

	public const Blocks = [
		['bl' => 'blockBl'],
	];


	public function main(array $ʟ_args): void
	{
%A%
		echo '
<p';
		$ʟ_tmp = ['title' => 'hello', 'lang' => isset($lang) ? $lang : null];
		$ʟ_tmp = [$ʟ_tmp[0] ?? null] === $ʟ_tmp ? $ʟ_tmp[0] : $ʟ_tmp;
		foreach ((array) $ʟ_tmp as $ʟ_nm => $ʟ_v) {
			if ($ʟ_tmp = LR\HtmlHelpers::formatAttribute($ʟ_nm, $ʟ_v)) {
				echo ' ', $ʟ_tmp /* line %d% */;
			}
		}
		echo '> </p>

<p';
		$ʟ_tmp = [['title' => 'hello']];
		$ʟ_tmp = [$ʟ_tmp[0] ?? null] === $ʟ_tmp ? $ʟ_tmp[0] : $ʟ_tmp;
		foreach ((array) $ʟ_tmp as $ʟ_nm => $ʟ_v) {
			if ($ʟ_tmp = LR\HtmlHelpers::formatAttribute($ʟ_nm, $ʟ_v)) {
				echo ' ', $ʟ_tmp /* line %d% */;
			}
		}
		echo '> </p>

';
		foreach ($iterator = $ʟ_it = new Latte\Essential\CachingIterator([1, 2, 3], $ʟ_it ?? null) as $foo) /* line %d% */ {
			echo '	<b';
			echo ($ʟ_tmp = array_filter([$iterator->even ? 'even' : null])) ? ' class="' . LR\Filters::escapeHtmlAttr(implode(" ", array_unique($ʟ_tmp))) . '"' : "" /* line %d% */;
			echo '>item</b>
';

		}
		$iterator = $ʟ_it = $ʟ_it->getParent();

		echo "\n";
		foreach ($iterator = $ʟ_it = new Latte\Essential\CachingIterator([1, 2, 3], $ʟ_it ?? null) as $foo) /* line %d% */ {
			echo '<p';
			echo ($ʟ_tmp = array_filter([$iterator->even ? 'even' : null])) ? ' class="' . LR\Filters::escapeHtmlAttr(implode(" ", array_unique($ʟ_tmp))) . '"' : "" /* line %d% */;
			echo '>';
			echo LR\Filters::escapeHtmlText($foo) /* line %d% */;
			echo '</p>
';

		}
		$iterator = $ʟ_it = $ʟ_it->getParent();

		echo '
<p';
		echo ($ʟ_tmp = array_filter(['foo', false ? 'first' : null, 'odd', true ? 'foo' : 'bar'])) ? ' class="' . LR\Filters::escapeHtmlAttr(implode(" ", array_unique($ʟ_tmp))) . '"' : "" /* line %d% */;
		echo '>n:class</p>

<p';
		echo ($ʟ_tmp = array_filter([false ? 'first' : null])) ? ' class="' . LR\Filters::escapeHtmlAttr(implode(" ", array_unique($ʟ_tmp))) . '"' : "" /* line %d% */;
		echo '>n:class empty</p>
<p';
		echo ($ʟ_tmp = array_filter([true ? 'bem--modifier' : null])) ? ' class="' . LR\Filters::escapeHtmlAttr(implode(" ", array_unique($ʟ_tmp))) . '"' : "" /* line %d% */;
		echo '>n:class with BEM</p>


';
		$this->renderBlock('bl', get_defined_vars()) /* line %d% */;
		echo '




<ul title="foreach">
';
		foreach ($people as $person) /* line %d% */ {
			echo '	<li>';
			echo LR\Filters::escapeHtmlText($person) /* line %d% */;
			echo '</li>
';

		}

		echo '</ul>

<ul title="foreach">
';
		foreach ($people as $person) /* line %d% */ {
			echo '	<li>
		';
			echo LR\Filters::escapeHtmlText($person) /* line %d% */;
			echo '
	</li>
';

		}

		echo '</ul>

<ul title="inner foreach">
	<li>
';
		foreach ($people as $person) /* line %d% */ {
			echo '		';
			echo LR\Filters::escapeHtmlText($person) /* line %d% */;
			echo "\n";

		}

		echo '	</li>
</ul>

<ul title="tag if">
	';
		$ʟ_tag[0] = '';
		if (true) /* line %d% */ {
			echo '<li>';
			$ʟ_tag[0] = '</li>' . $ʟ_tag[0];
		}
		echo '
		';
		echo LR\Filters::escapeHtmlText($person) /* line %d% */;
		echo '
	';
		echo $ʟ_tag[0];
		echo '
</ul>

<ul title="for">
';
		for ($i = 0;
		$i < 3;
		$i++) /* line %d% */ {
			echo '	<li>';
			echo LR\Filters::escapeHtmlText($i) /* line %d% */;
			echo '</li>
';

		}
		echo '</ul>

<ul title="white">
';
		while (--$i > 0) /* line %d% */ {
			echo '	<li>';
			echo LR\Filters::escapeHtmlText($i) /* line %d% */;
			echo '</li>
';

		}
		echo '</ul>

';
		if (true) /* line %d% */ {
			echo '<p>
	<div><p>true</div>
</p>
';
		}
		echo "\n";
		if (true) /* line %d% */ {
			echo '<p>
	<div><p>true</p></div>
</p>
';
		}
		echo "\n";
		if (false) /* line %d% */ {
			echo '<p>
	<div><p>false</div>
</p>
';
		}
		echo "\n";
		if (false) /* line %d% */ {
			echo '<p>
	<div><p>false</p></div>
</p>
';
		}
		echo "\n";
		if (strlen('{$name}') > 5) /* line %d% */ {
			echo '<p>noLatte</p>
';
		}
		echo '
<ul title="if + foreach">
';
		foreach ($people as $person) /* line %d% */ {
			if (strlen($person) === 4) /* line %d% */ {
				echo '	<li>';
				echo LR\Filters::escapeHtmlText($person) /* line %d% */;
				echo '</li>
';
			}

		}

		echo '</ul>

<ul title="if + inner-if + inner-foreach">
';
		if (empty($iterator)) /* line %d% */ {
			echo '	<li>';
			foreach ($people as $person) /* line %d% */ {
				if (strlen($person) === 4) /* line %d% */ {
					echo LR\Filters::escapeHtmlText($person) /* line %d% */;
				}

			}

			echo '</li>
';
		}
		echo '</ul>

<ul title="inner-if + inner-foreach">
';
		foreach ($people as $person) /* line %d% */ {
			if (strlen($person) === 4) /* line %d% */ {
				echo '	<li>';
				echo LR\Filters::escapeHtmlText(($this->filters->lower)($person)) /* line %d% */;
				echo '</li>
';
			}

		}

		echo '</ul>

';
		$ʟ_tag[1] = '';
		if (true) /* line %d% */ {
			echo '<b>';
			$ʟ_tag[1] = '</b>' . $ʟ_tag[1];
		}
		echo 'bold';
		echo $ʟ_tag[1];
		echo ' ';
		$ʟ_tag[2] = '';
		if (false) /* line %d% */ {
			echo '<b>';
			$ʟ_tag[2] = '</b>' . $ʟ_tag[2];
		}
		echo 'normal';
		echo $ʟ_tag[2];
		echo '

';
		$ʟ_tag[3] = '';
		if (true) /* line %d% */ {
			echo '<b';
			echo ($ʟ_tmp = array_filter(['first'])) ? ' class="' . LR\Filters::escapeHtmlAttr(implode(" ", array_unique($ʟ_tmp))) . '"' : "" /* line %d% */;
			echo '>';
			$ʟ_tag[3] = '</b>' . $ʟ_tag[3];
		}
		echo 'bold';
		echo $ʟ_tag[3];
		echo '

<meta>
';
		if (true) /* line %d% */ {
			echo '<meta>
';
		}
		echo '<meta>

';
		foreach ([0] as $foo) /* line %d% */ {
			if (1) /* line %d% */ {
				$ʟ_tag[4] = '';
				foreach ([1] as $foo) /* line %d% */ {
					if (0) /* line %d% */ {
						echo '<span>';
						$ʟ_tag[4] = '</span>' . $ʟ_tag[4];
					}

				}

				foreach ([2] as $foo) /* line %d% */ {
					if (2) /* line %d% */ {
						echo 'Hello';
					}

				}

				echo $ʟ_tag[4];
				echo "\n";
			}

		}

		echo '

';
		$ʟ_tag[5] = '';
		if (true) /* line %d% */ {
			echo '<div>';
			$ʟ_tag[5] = '</div>' . $ʟ_tag[5];
		}
		echo "\n";
		$ʟ_try[6] = [$ʟ_it ?? null];
		ob_start(fn() => '');
		try /* line %d% */ {
			echo '	';
			$ʟ_tag[7] = '';
			if (false) /* line %d% */ {
				echo '<span>';
				$ʟ_tag[7] = '</span>' . $ʟ_tag[7];
			}
			echo "\n";
			throw new Latte\Essential\RollbackException;
			echo '	';
			echo $ʟ_tag[7];
			echo "\n";

		} catch (Throwable $ʟ_e) {
			ob_clean();
			if (!($ʟ_e instanceof Latte\Essential\RollbackException) && isset($this->global->coreExceptionHandler)) {
				($this->global->coreExceptionHandler)($ʟ_e, $this);
			}
		} finally {
			echo ob_get_clean();
			$iterator = $ʟ_it = $ʟ_try[6][0];
		}echo $ʟ_tag[5];
		echo '


<ul title="foreach break">
';
		foreach ($people as $person) /* line %d% */ {
			echo '	<li>';
			try {
				echo LR\Filters::escapeHtmlText($person) /* line %d% */;
				if (true) /* line %d% */ break;
			} finally {
				echo '</li>';
			}
			echo "\n";

		}

		echo '</ul>

<ul title="foreach continue">
';
		foreach ($people as $person) /* line %d% */ {
			echo '	<li>';
			try {
				echo LR\Filters::escapeHtmlText($person) /* line %d% */;
				if (true) /* line %d% */ continue;
			} finally {
				echo '</li>';
			}
			echo "\n";

		}

		echo '</ul>


<ul title="inner foreach break">
	<li>';
		foreach ($people as $person) /* line %d% */ {
			echo LR\Filters::escapeHtmlText($person) /* line %d% */;
			if (true) /* line %d% */ break;

		}

		echo '</li>
</ul>

<ul title="inner foreach continue">
	<li>';
		foreach ($people as $person) /* line %d% */ {
			echo LR\Filters::escapeHtmlText($person) /* line %d% */;
			if (true) /* line %d% */ continue;

		}

		echo '</li>
</ul>


';
		ob_start(fn() => '');
		try {
			$ʟ_tag[8] = '';
			$ʟ_tmp = LR\HtmlHelpers::validateTagChange('span', 'div');
			echo '<', $ʟ_tmp /* line %d% */;
			$ʟ_tag[8] = '</' . $ʟ_tmp . '>' . $ʟ_tag[8];
			echo '>';
			ob_start();
			try {
				echo 'n:tag & n:ifcontent';

			} finally {
				$ʟ_ifc[0] = rtrim(ob_get_flush()) === '';
			}
			echo $ʟ_tag[8];
			echo "\n";

		} finally {
			if ($ʟ_ifc[0] ?? null) {
				ob_end_clean();

			} else {
				echo ob_get_clean();
			}
		}
		ob_start(fn() => '');
		try {
			$ʟ_tag[9] = '';
			$ʟ_tmp = LR\HtmlHelpers::validateTagChange('span', 'div');
			echo '<', $ʟ_tmp /* line %d% */;
			$ʟ_tag[9] = '</' . $ʟ_tmp . '>' . $ʟ_tag[9];
			echo '>';
			ob_start();
			try {

			} finally {
				$ʟ_ifc[1] = rtrim(ob_get_flush()) === '';
			}
			echo $ʟ_tag[9];
			echo "\n";

		} finally {
			if ($ʟ_ifc[1] ?? null) {
				ob_end_clean();

			} else {
				echo ob_get_clean();
			}
		}
		echo "\n";
		if (1) /* line %d% */ {
			$ʟ_tag[10] = '';
			$ʟ_tmp = LR\HtmlHelpers::validateTagChange('span', 'div');
			echo '<', $ʟ_tmp /* line %d% */;
			$ʟ_tag[10] = '</' . $ʟ_tmp . '>' . $ʟ_tag[10];
			echo '>n:tag & n:if=1';
			echo $ʟ_tag[10];
			echo "\n";
		}
		if (0) /* line %d% */ {
			$ʟ_tag[11] = '';
			$ʟ_tmp = LR\HtmlHelpers::validateTagChange('span', 'div');
			echo '<', $ʟ_tmp /* line %d% */;
			$ʟ_tag[11] = '</' . $ʟ_tmp . '>' . $ʟ_tag[11];
			echo '>n:tag & n:if=0';
			echo $ʟ_tag[11];
			echo "\n";
		}
	}


	public function prepare(): array
	{
		extract($this->params);

		if (!$this->getReferringTemplate() || $this->getReferenceType() === 'extends') {
			foreach (array_intersect_key(['foo' => '6, 10, 94, 94, 94', 'person' => '19, 27, 31, 37, 75, 79, 82, 107, 111, 116, 120'], $this->params) as $ʟ_v => $ʟ_l) {
				trigger_error("Variable \$$ʟ_v overwritten in foreach on line $ʟ_l");
			}
		}
		return get_defined_vars();
	}


	/** n:block="bl" on line 18 */
	public function blockBl(array $ʟ_args): void
	{
		extract($this->params);
		extract($ʟ_args);
		unset($ʟ_args);

		echo '<ul title="block + if + foreach">
';
		foreach ($people as $person) /* line %d% */ {
			if (strlen($person) === 4) /* line %d% */ {
				echo '	<li>';
				echo LR\Filters::escapeHtmlText($person) /* line %d% */;
				echo '</li>
';
			}

		}

		echo '</ul>
';
	}
}
