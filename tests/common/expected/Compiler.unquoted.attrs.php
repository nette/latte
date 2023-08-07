<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{

	public function main(array $ʟ_args): void
	{
%A%
		echo '<span title=';
		echo '"' . LR\Filters::escapeHtmlAttr($x) . '"' /* line %d% */;
		echo ' class=';
		echo '"' . LR\Filters::escapeHtmlAttr($x) . '"' /* line %d% */;
		echo '></span>

<span title=';
		echo '"' . LR\Filters::escapeHtmlAttr($x) . '"' /* line %d% */;
		echo ' ';
		echo LR\Filters::escapeHtmlTag($x) /* line %d% */;
		echo '></span>

<span title=';
		if (true) /* line %d% */ {
			echo '"' . LR\Filters::escapeHtmlAttr($x) . '"' /* line %d% */;
		} else /* line %d% */ {
			echo '"item"';
		}
		echo '></span>

<span ';
		echo LR\Filters::escapeHtmlTag('title') /* line %d% */;
		echo '=';
		echo '"' . LR\Filters::escapeHtmlAttr($x) . '"' /* line %d% */;
		echo '></span>

<span attr=c';
		echo LR\Filters::escapeHtmlTag($x) /* line %d% */;
		echo 'd></span>

<span onclick=';
		echo '"' . LR\Filters::escapeHtmlAttr(LR\Filters::escapeJs($x)) . '"' /* line %d% */;
		echo ' ';
		echo LR\Filters::escapeHtmlTag($x) /* line %d% */;
		echo '></span>

<span onclick=c';
		echo LR\Filters::escapeHtmlTag($x) /* line %d% */;
		echo 'd></span>

<span attr';
		echo LR\Filters::escapeHtmlTag($x) /* line %d% */;
		echo 'b=c';
		echo LR\Filters::escapeHtmlTag($x) /* line %d% */;
		echo 'd></span>
';
	}
}
