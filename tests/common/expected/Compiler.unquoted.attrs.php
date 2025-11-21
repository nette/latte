<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{

	public function main(array $ʟ_args): void
	{
%A%
		echo '<span title=hello></span>

<span';
		echo LR\HtmlHelpers::formatAttribute(' title', $x) /* pos %d%:%d% */;
		echo LR\HtmlHelpers::formatListAttribute(' class', $x) /* pos %d%:%d% */;
		echo '></span>

<span';
		echo LR\HtmlHelpers::formatAttribute(' title', $x) /* pos %d%:%d% */;
		echo ' ';
		echo LR\HtmlHelpers::escapeTag($x) /* pos %d%:%d% */;
		echo '></span>

<span title="';
		if (true) /* pos %d%:%d% */ {
			echo LR\HtmlHelpers::escapeAttr($x) /* pos %d%:%d% */;
		} else /* pos %d%:%d% */ {
			echo 'item';
		}
		echo '"></span>

<span ';
		echo LR\HtmlHelpers::escapeTag('title') /* pos %d%:%d% */;
		echo '="';
		echo LR\HtmlHelpers::escapeAttr($x) /* pos %d%:%d% */;
		echo '"></span>

<span attr="c';
		echo LR\HtmlHelpers::escapeAttr($x) /* pos %d%:%d% */;
		echo 'd"></span>

<span';
		echo LR\HtmlHelpers::formatAttribute(' onclick', $x) /* pos %d%:%d% */;
		echo ' ';
		echo LR\HtmlHelpers::escapeTag($x) /* pos %d%:%d% */;
		echo '></span>

<span onclick="c';
		echo LR\HtmlHelpers::escapeAttr(LR\Helpers::escapeJs($x)) /* pos %d%:%d% */;
		echo 'd"></span>

<span attr';
		echo LR\HtmlHelpers::escapeTag($x) /* pos %d%:%d% */;
		echo 'b="c';
		echo LR\HtmlHelpers::escapeAttr($x) /* pos %d%:%d% */;
		echo 'd"></span>
';
	}
}
