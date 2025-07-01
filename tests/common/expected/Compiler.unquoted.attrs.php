<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{

	public function main(array $ʟ_args): void
	{
%A%
		echo '<span title="';
		echo LR\Filters::escapeHtmlAttr($x) /* line %d%:%d% */;
		echo '" class="';
		echo LR\Filters::escapeHtmlAttr($x) /* line %d%:%d% */;
		echo '"></span>

<span title="';
		echo LR\Filters::escapeHtmlAttr($x) /* line %d%:%d% */;
		echo '" ';
		echo LR\Filters::escapeHtmlTag($x) /* line %d%:%d% */;
		echo '></span>

<span title="';
		if (true) /* line %d%:%d% */ {
			echo LR\Filters::escapeHtmlAttr($x) /* line %d%:%d% */;
		} else /* line %d%:%d% */ {
			echo 'item';
		}
		echo '"></span>

<span ';
		echo LR\Filters::escapeHtmlTag('title') /* line %d%:%d% */;
		echo '="';
		echo LR\Filters::escapeHtmlAttr($x) /* line %d%:%d% */;
		echo '"></span>

<span attr="c';
		echo LR\Filters::escapeHtmlAttr($x) /* line %d%:%d% */;
		echo 'd"></span>

<span onclick="';
		echo LR\Filters::escapeHtmlAttr(LR\Filters::escapeJs($x)) /* line %d%:%d% */;
		echo '" ';
		echo LR\Filters::escapeHtmlTag($x) /* line %d%:%d% */;
		echo '></span>

<span onclick="c';
		echo LR\Filters::escapeHtmlAttr(LR\Filters::escapeJs($x)) /* line %d%:%d% */;
		echo 'd"></span>

<span attr';
		echo LR\Filters::escapeHtmlTag($x) /* line %d%:%d% */;
		echo 'b="c';
		echo LR\Filters::escapeHtmlAttr($x) /* line %d%:%d% */;
		echo 'd"></span>
';
	}
}
