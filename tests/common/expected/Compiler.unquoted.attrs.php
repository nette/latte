<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{

	public function main(array $ʟ_args): void
	{
%A%
		echo '<span title=hello></span>

<span';
		echo LR\HtmlHelpers::formatCommonAttribute(' title', $x) /* line %d%:%d% */;
		echo LR\HtmlHelpers::formatCommonAttribute(' class', $x) /* line %d%:%d% */;
		echo '></span>

<span';
		echo LR\HtmlHelpers::formatCommonAttribute(' title', $x) /* line %d%:%d% */;
		echo ' ';
		echo LR\HtmlHelpers::escapeTag($x) /* line %d%:%d% */;
		echo '></span>

<span title="';
		if (true) /* line %d%:%d% */ {
			echo LR\HtmlHelpers::escapeAttr($x) /* line %d%:%d% */;
		} else /* line %d%:%d% */ {
			echo 'item';
		}
		echo '"></span>

<span ';
		echo LR\HtmlHelpers::escapeTag('title') /* line %d%:%d% */;
		echo '="';
		echo LR\HtmlHelpers::escapeAttr($x) /* line %d%:%d% */;
		echo '"></span>

<span attr="c';
		echo LR\HtmlHelpers::escapeAttr($x) /* line %d%:%d% */;
		echo 'd"></span>

<span';
		echo LR\HtmlHelpers::formatCommonAttribute(' onclick', $x) /* line %d%:%d% */;
		echo ' ';
		echo LR\HtmlHelpers::escapeTag($x) /* line %d%:%d% */;
		echo '></span>

<span onclick="c';
		echo LR\HtmlHelpers::escapeAttr(LR\Helpers::escapeJs($x)) /* line %d%:%d% */;
		echo 'd"></span>

<span attr';
		echo LR\HtmlHelpers::escapeTag($x) /* line %d%:%d% */;
		echo 'b="c';
		echo LR\HtmlHelpers::escapeAttr($x) /* line %d%:%d% */;
		echo 'd"></span>
';
	}
}
