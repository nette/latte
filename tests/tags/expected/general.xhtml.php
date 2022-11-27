<?php

use Latte\Runtime as LR;

/** source: %A% */
final class Template%a% extends Latte\Runtime\Template
{
	protected const BLOCKS = [
		['menu' => 'blockMenu'],
	];

	protected const CONTENT_TYPE = 'xhtml';


	public function main(): array
	{
		extract($this->params);
		echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<input /> <input /> <input />

<input checked /> <input checked="checked" />

<button></button>

{ test} {"test} {\'test}

';
		echo LR\Filters::escapeHtmlText((string) (bool) (float) (int) (array) 10) /* line %d% */;
		echo '


';
		$iterations = 0;
		foreach ($iterator = $ʟ_it = new LR\CachingIterator(array(true), $ʟ_it ?? null) as $foo) /* line %d% */ {
			$iterations = 0;
			foreach ($iterator = $ʟ_it = new LR\CachingIterator($people, $ʟ_it ?? null) as $person) /* line %d% */ {
				echo '	';
				if ($iterator->isFirst()) /* line %d% */ {
					echo '<ul>';
				}
				echo '
	<li id="item-';
				echo LR\Filters::escapeHtmlAttr($iterator->getCounter()) /* line %d% */;
				echo '" class="';
				echo LR\Filters::escapeHtmlAttr($iterator->isOdd() ? 'odd' : 'even') /* line %d% */;
				echo '">';
				echo LR\Filters::escapeHtmlText($person) /* line %d% */;
				echo '</li>
	';
				if ($iterator->isLast()) /* line %d% */ {
					echo '</ul>';
				}
				echo "\n";
				$iterations++;
			}
			$iterator = $ʟ_it = $ʟ_it->getParent();
			$iterations++;
		}
		$iterator = $ʟ_it = $ʟ_it->getParent();
		echo '

';
		$counter = 0 /* line %d% */;
		if ($this->getParentName()) {
			return get_defined_vars();
		}
		$this->renderBlock('menu', get_defined_vars()) /* line %d% */;
		echo "\n";
		return get_defined_vars();
	}


	public function prepare(): void
	{
		extract($this->params);
		if (!$this->getReferringTemplate() || $this->getReferenceType() === "extends") {
			foreach (array_intersect_key(['person' => '15', 'foo' => '14', 'item' => '26'], $this->params) as $ʟ_v => $ʟ_l) {
				trigger_error("Variable \$$ʟ_v overwritten in foreach on line $ʟ_l");
			}
		}

	}


	/** {block menu} on line %d% */
	public function blockMenu(array $ʟ_args): void
	{
		extract($this->params);
		extract($ʟ_args);
		unset($ʟ_args);
		echo '<ul>
';
		$iterations = 0;
		foreach ($iterator = $ʟ_it = new LR\CachingIterator($menu, $ʟ_it ?? null) as $item) /* line %d% */ {
			echo '	<li>';
			echo LR\Filters::escapeHtmlText($counter++) /* line %d% */;
			echo ' ';
			if (is_array($item)) /* line %d% */ {
				echo ' ';
				$this->renderBlock('menu', ['menu' => $item] + get_defined_vars(), 'xhtml') /* line %d% */;
				echo ' ';
			} else /* line %d% */ {
				echo LR\Filters::escapeHtmlText($item) /* line %d% */;
			}
			echo '</li>
';
			$iterations++;
		}
		$iterator = $ʟ_it = $ʟ_it->getParent();
		echo '</ul>
';
	}

}
