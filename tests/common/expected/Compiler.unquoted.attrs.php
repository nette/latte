<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{

	public function main(): array
	{
%A%
		echo '<span title=';
		echo LR\Filters::escapeHtmlAttrUnquoted($x) /* line %d% */;
		echo ' class=';
		echo LR\Filters::escapeHtmlAttrUnquoted($x) /* line %d% */;
		echo '></span>

<span title=';
		echo LR\Filters::escapeHtmlAttrUnquoted($x) /* line %d% */;
		echo ' ';
		echo LR\Filters::escapeHtmlAttrUnquoted($x) /* line %d% */;
		echo '></span>

<span title=';
		if (true) /* line %d% */ {
			echo LR\Filters::escapeHtmlAttrUnquoted($x) /* line %d% */;
		} else /* line %d% */ {
			echo '"item"';
		}
		echo '></span>

<span title=';
		if (true) /* line %d% */ {
			echo LR\Filters::escapeHtmlAttrUnquoted($x) /* line %d% */;
			echo ' ';
			echo LR\Filters::escapeHtmlAttrUnquoted($x) /* line %d% */;
		} else /* line %d% */ {
			echo '"item"';
		}
		echo '></span>

<span ';
		echo LR\Filters::escapeHtmlAttrUnquoted('title') /* line %d% */;
		echo '=';
		echo LR\Filters::escapeHtmlAttrUnquoted($x) /* line %d% */;
		echo '></span>

<span attr';
		echo LR\Filters::escapeHtmlAttrUnquoted($x) /* line %d% */;
		echo 'b=c';
		echo LR\Filters::escapeHtmlAttrUnquoted($x) /* line %d% */;
		echo 'd></span>
';
		return get_defined_vars();
	}

}
