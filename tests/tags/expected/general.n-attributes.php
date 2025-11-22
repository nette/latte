<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{
	public const Blocks = [
		['bl' => 'blockBl'],
	];


	public function main(array $ʟ_args): void
	{
%A%
		echo '
<p';
		$ʟ_tmp = ['title' => 'hello', 'lang' => isset($lang) ? $lang : null];
		echo Latte\Essential\Nodes\NAttrNode::attrs($ʟ_tmp, false) /* pos %d%:%d% */;
		echo '> </p>

<p';
		$ʟ_tmp = [['title' => 'hello']];
		echo Latte\Essential\Nodes\NAttrNode::attrs($ʟ_tmp, false) /* pos %d%:%d% */;
		echo '> </p>

';
		foreach ($iterator = $ʟ_it = new Latte\Essential\CachingIterator([1, 2, 3], $ʟ_it ?? null) as $foo) /* pos %d%:%d% */ {
			echo '	<b';
			echo ($ʟ_tmp = array_filter([$iterator->even ? 'even' : null])) ? ' class="' . LR\Filters::escapeHtmlAttr(implode(" ", array_unique($ʟ_tmp))) . '"' : "" /* pos %d%:%d% */;
			echo '>item</b>
';

		}
		$iterator = $ʟ_it = $ʟ_it->getParent();

		echo "\n";
		foreach ($iterator = $ʟ_it = new Latte\Essential\CachingIterator([1, 2, 3], $ʟ_it ?? null) as $foo) /* pos %d%:%d% */ {
			echo '<p';
			echo ($ʟ_tmp = array_filter([$iterator->even ? 'even' : null])) ? ' class="' . LR\Filters::escapeHtmlAttr(implode(" ", array_unique($ʟ_tmp))) . '"' : "" /* pos %d%:%d% */;
			echo '>';
			echo LR\Filters::escapeHtmlText($foo) /* pos %d%:%d% */;
			echo '</p>
';

		}
		$iterator = $ʟ_it = $ʟ_it->getParent();

		echo '
<p';
		echo ($ʟ_tmp = array_filter(['foo', false ? 'first' : null, 'odd', true ? 'foo' : 'bar'])) ? ' class="' . LR\Filters::escapeHtmlAttr(implode(" ", array_unique($ʟ_tmp))) . '"' : "" /* pos %d%:%d% */;
		echo '>n:class</p>

<p';
		echo ($ʟ_tmp = array_filter([false ? 'first' : null])) ? ' class="' . LR\Filters::escapeHtmlAttr(implode(" ", array_unique($ʟ_tmp))) . '"' : "" /* pos %d%:%d% */;
		echo '>n:class empty</p>
<p';
		echo ($ʟ_tmp = array_filter([true ? 'bem--modifier' : null])) ? ' class="' . LR\Filters::escapeHtmlAttr(implode(" ", array_unique($ʟ_tmp))) . '"' : "" /* pos %d%:%d% */;
		echo '>n:class with BEM</p>


';
		$this->renderBlock('bl', get_defined_vars()) /* pos %d%:%d% */;
		echo '




<ul title="foreach">
';
		foreach ($people as $person) /* pos %d%:%d% */ {
			echo '	<li>';
			echo LR\Filters::escapeHtmlText($person) /* pos %d%:%d% */;
			echo '</li>
';

		}

		echo '</ul>

<ul title="foreach">
';
		foreach ($people as $person) /* pos %d%:%d% */ {
			echo '	<li>
		';
			echo LR\Filters::escapeHtmlText($person) /* pos %d%:%d% */;
			echo '
	</li>
';

		}

		echo '</ul>

<ul title="inner foreach">
	<li>
';
		foreach ($people as $person) /* pos %d%:%d% */ {
			echo '		';
			echo LR\Filters::escapeHtmlText($person) /* pos %d%:%d% */;
			echo "\n";

		}

		echo '	</li>
</ul>

<ul title="tag if">
	';
		$ʟ_tag = '';
		if (true) /* pos %d%:%d% */ {
			$ʟ_tag = '</li>' . $ʟ_tag;
			echo '<li>';
		}
		$ʟ_tags[0] = $ʟ_tag;
		echo '
		';
		echo LR\Filters::escapeHtmlText($person) /* pos %d%:%d% */;
		echo '
	';
		echo $ʟ_tags[0];
		echo '
</ul>

<ul title="for">
';
		for ($i = 0;
		$i < 3;
		$i++) /* pos %d%:%d% */ {
			echo '	<li>';
			echo LR\Filters::escapeHtmlText($i) /* pos %d%:%d% */;
			echo '</li>
';

		}
		echo '</ul>

<ul title="white">
';
		while (--$i > 0) /* pos %d%:%d% */ {
			echo '	<li>';
			echo LR\Filters::escapeHtmlText($i) /* pos %d%:%d% */;
			echo '</li>
';

		}
		echo '</ul>

';
		if (true) /* pos %d%:%d% */ {
			echo '<p>
	<div><p>true</div>
</p>
';
		}
		echo "\n";
		if (true) /* pos %d%:%d% */ {
			echo '<p>
	<div><p>true</p></div>
</p>
';
		}
		echo "\n";
		if (false) /* pos %d%:%d% */ {
			echo '<p>
	<div><p>false</div>
</p>
';
		}
		echo "\n";
		if (false) /* pos %d%:%d% */ {
			echo '<p>
	<div><p>false</p></div>
</p>
';
		}
		echo "\n";
		if (strlen('{$name}') > 5) /* pos %d%:%d% */ {
			echo '<p>noLatte</p>
';
		}
		echo '
<ul title="if + foreach">
';
		foreach ($people as $person) /* pos %d%:%d% */ {
			if (strlen($person) === 4) /* pos %d%:%d% */ {
				echo '	<li>';
				echo LR\Filters::escapeHtmlText($person) /* pos %d%:%d% */;
				echo '</li>
';
			}

		}

		echo '</ul>

<ul title="if + inner-if + inner-foreach">
';
		if (empty($iterator)) /* pos %d%:%d% */ {
			echo '	<li>';
			foreach ($people as $person) /* pos %d%:%d% */ {
				if (strlen($person) === 4) /* pos %d%:%d% */ {
					echo LR\Filters::escapeHtmlText($person) /* pos %d%:%d% */;
				}

			}

			echo '</li>
';
		}
		echo '</ul>

<ul title="inner-if + inner-foreach">
';
		foreach ($people as $person) /* pos %d%:%d% */ {
			if (strlen($person) === 4) /* pos %d%:%d% */ {
				echo '	<li>';
				echo LR\Filters::escapeHtmlText(($this->filters->lower)($person)) /* pos %d%:%d% */;
				echo '</li>
';
			}

		}

		echo '</ul>

';
		$ʟ_tag = '';
		if (true) /* pos %d%:%d% */ {
			$ʟ_tag = '</b>' . $ʟ_tag;
			echo '<b>';
		}
		$ʟ_tags[1] = $ʟ_tag;
		echo 'bold';
		echo $ʟ_tags[1];
		echo ' ';
		$ʟ_tag = '';
		if (false) /* pos %d%:%d% */ {
			$ʟ_tag = '</b>' . $ʟ_tag;
			echo '<b>';
		}
		$ʟ_tags[2] = $ʟ_tag;
		echo 'normal';
		echo $ʟ_tags[2];
		echo '

';
		$ʟ_tag = '';
		if (true) /* pos %d%:%d% */ {
			$ʟ_tag = '</b>' . $ʟ_tag;
			echo '<b';
			echo ($ʟ_tmp = array_filter(['first'])) ? ' class="' . LR\Filters::escapeHtmlAttr(implode(" ", array_unique($ʟ_tmp))) . '"' : "" /* pos %d%:%d% */;
			echo '>';
		}
		$ʟ_tags[3] = $ʟ_tag;
		echo 'bold';
		echo $ʟ_tags[3];
		echo '

<meta>
';
		if (true) /* pos %d%:%d% */ {
			echo '<meta>
';
		}
		echo '<meta>

';
		foreach ([0] as $foo) /* pos %d%:%d% */ {
			if (1) /* pos %d%:%d% */ {
				$ʟ_tag = '';
				foreach ([1] as $foo) /* pos %d%:%d% */ {
					if (0) /* pos %d%:%d% */ {
						$ʟ_tag = '</span>' . $ʟ_tag;
						echo '<span>';
					}

				}

				$ʟ_tags[4] = $ʟ_tag;
				foreach ([2] as $foo) /* pos %d%:%d% */ {
					if (2) /* pos %d%:%d% */ {
						echo 'Hello';
					}

				}

				echo $ʟ_tags[4];
				echo "\n";
			}

		}

		echo '

';
		$ʟ_tag = '';
		if (true) /* pos %d%:%d% */ {
			$ʟ_tag = '</div>' . $ʟ_tag;
			echo '<div>';
		}
		$ʟ_tags[5] = $ʟ_tag;
		echo "\n";
		$ʟ_try[6] = [$ʟ_it ?? null];
		ob_start(fn() => '');
		try /* pos %d%:%d% */ {
			echo '	';
			$ʟ_tag = '';
			if (false) /* pos %d%:%d% */ {
				$ʟ_tag = '</span>' . $ʟ_tag;
				echo '<span>';
			}
			$ʟ_tags[7] = $ʟ_tag;
			echo "\n";
			throw new Latte\Essential\RollbackException;
			echo '	';
			echo $ʟ_tags[7];
			echo "\n";

		} catch (Throwable $ʟ_e) {
			ob_clean();
			if (!($ʟ_e instanceof Latte\Essential\RollbackException) && isset($this->global->coreExceptionHandler)) {
				($this->global->coreExceptionHandler)($ʟ_e, $this);
			}
		} finally {
			echo ob_get_clean();
			$iterator = $ʟ_it = $ʟ_try[6][0];
		}
		echo $ʟ_tags[5];
		echo '


<ul title="foreach break">
';
		foreach ($people as $person) /* pos %d%:%d% */ {
			echo '	<li>';
			try {
				echo LR\Filters::escapeHtmlText($person) /* pos %d%:%d% */;
				if (true) /* pos %d%:%d% */ break;
			} finally {
				echo '</li>';
			}
			echo "\n";

		}

		echo '</ul>

<ul title="foreach continue">
';
		foreach ($people as $person) /* pos %d%:%d% */ {
			echo '	<li>';
			try {
				echo LR\Filters::escapeHtmlText($person) /* pos %d%:%d% */;
				if (true) /* pos %d%:%d% */ continue;
			} finally {
				echo '</li>';
			}
			echo "\n";

		}

		echo '</ul>


<ul title="inner foreach break">
	<li>';
		foreach ($people as $person) /* pos %d%:%d% */ {
			echo LR\Filters::escapeHtmlText($person) /* pos %d%:%d% */;
			if (true) /* pos %d%:%d% */ break;

		}

		echo '</li>
</ul>

<ul title="inner foreach continue">
	<li>';
		foreach ($people as $person) /* pos %d%:%d% */ {
			echo LR\Filters::escapeHtmlText($person) /* pos %d%:%d% */;
			if (true) /* pos %d%:%d% */ continue;

		}

		echo '</li>
</ul>


';
		ob_start(fn() => '');
		try {
			$ʟ_tag = '';
			$ʟ_tmp = LR\HtmlHelpers::validateTagChange('span', 'div');
			$ʟ_tag = '</' . $ʟ_tmp . '>' . $ʟ_tag;
			echo '<', $ʟ_tmp /* pos %d%:%d% */;
			echo '>';
			$ʟ_tags[8] = $ʟ_tag;
			ob_start();
			try {
				echo 'n:tag & n:ifcontent';

			} finally {
				$ʟ_ifc[0] = rtrim(ob_get_flush()) === '';
			}
			echo $ʟ_tags[8];
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
			$ʟ_tag = '';
			$ʟ_tmp = LR\HtmlHelpers::validateTagChange('span', 'div');
			$ʟ_tag = '</' . $ʟ_tmp . '>' . $ʟ_tag;
			echo '<', $ʟ_tmp /* pos %d%:%d% */;
			echo '>';
			$ʟ_tags[9] = $ʟ_tag;
			ob_start();
			try {

			} finally {
				$ʟ_ifc[1] = rtrim(ob_get_flush()) === '';
			}
			echo $ʟ_tags[9];
			echo "\n";

		} finally {
			if ($ʟ_ifc[1] ?? null) {
				ob_end_clean();

			} else {
				echo ob_get_clean();
			}
		}
		echo "\n";
		if (1) /* pos %d%:%d% */ {
			$ʟ_tag = '';
			$ʟ_tmp = LR\HtmlHelpers::validateTagChange('span', 'div');
			$ʟ_tag = '</' . $ʟ_tmp . '>' . $ʟ_tag;
			echo '<', $ʟ_tmp /* pos %d%:%d% */;
			echo '>';
			$ʟ_tags[10] = $ʟ_tag;
			echo 'n:tag & n:if=1';
			echo $ʟ_tags[10];
			echo "\n";
		}
		if (0) /* pos %d%:%d% */ {
			$ʟ_tag = '';
			$ʟ_tmp = LR\HtmlHelpers::validateTagChange('span', 'div');
			$ʟ_tag = '</' . $ʟ_tmp . '>' . $ʟ_tag;
			echo '<', $ʟ_tmp /* pos %d%:%d% */;
			echo '>';
			$ʟ_tags[11] = $ʟ_tag;
			echo 'n:tag & n:if=0';
			echo $ʟ_tags[11];
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


	/** n:block="bl" on line %d% */
	public function blockBl(array $ʟ_args): void
	{
		extract($this->params);
		extract($ʟ_args);
		unset($ʟ_args);

		echo '<ul title="block + if + foreach">
';
		foreach ($people as $person) /* pos %d%:%d% */ {
			if (strlen($person) === 4) /* pos %d%:%d% */ {
				echo '	<li>';
				echo LR\Filters::escapeHtmlText($person) /* pos %d%:%d% */;
				echo '</li>
';
			}

		}

		echo '</ul>
';
	}
}
