<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{

	public function main(array $ʟ_args): void
	{
%A%
		echo '<span ';
		echo LR\HtmlHelpers::formatAttribute('title', $x) /* line %d% */;
		echo ' ';
		echo LR\HtmlHelpers::formatAttribute('class', $x) /* line %d% */;
		echo '></span>

<span ';
		echo LR\HtmlHelpers::formatAttribute('title', $x) /* line %d% */;
		echo ' ';
		echo LR\HtmlHelpers::escapeTag($x) /* line %d% */;
		echo '></span>

<span title="';
		if (true) /* line %d% */ {
			echo LR\HtmlHelpers::escapeAttr($x) /* line %d% */;
		} else /* line %d% */ {
			echo 'item';
		}
		echo '"></span>

<span ';
		echo LR\HtmlHelpers::escapeTag('title') /* line %d% */;
		echo '="';
		echo LR\HtmlHelpers::escapeAttr($x) /* line %d% */;
		echo '"></span>

<span attr="c';
		echo LR\HtmlHelpers::escapeAttr($x) /* line %d% */;
		echo 'd"></span>

<span ';
		echo LR\HtmlHelpers::formatAttribute('onclick', $x) /* line %d% */;
		echo ' ';
		echo LR\HtmlHelpers::escapeTag($x) /* line %d% */;
		echo '></span>

<span onclick="c';
		echo LR\HtmlHelpers::escapeAttr(LR\Helpers::escapeJs($x)) /* line %d% */;
		echo 'd"></span>

<span attr';
		echo LR\HtmlHelpers::escapeTag($x) /* line %d% */;
		echo 'b="c';
		echo LR\HtmlHelpers::escapeAttr($x) /* line %d% */;
		echo 'd"></span>
';
	}
}
