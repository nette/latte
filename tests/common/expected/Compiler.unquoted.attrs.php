<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{

	public function main(array $ʟ_args): void
	{
%A%
		echo '<span title=hello></span>

<span title="';
		echo LR\Filters::escapeHtmlAttr($x) /* pos %d%:%d% */;
		echo '" class="';
		echo LR\Filters::escapeHtmlAttr($x) /* pos %d%:%d% */;
		echo '"></span>

<span title="';
		echo LR\Filters::escapeHtmlAttr($x) /* pos %d%:%d% */;
		echo '" ';
		echo LR\Filters::escapeHtmlTag($x) /* pos %d%:%d% */;
		echo '></span>

<span title="';
		if (true) /* pos %d%:%d% */ {
			echo LR\Filters::escapeHtmlAttr($x) /* pos %d%:%d% */;
		} else /* pos %d%:%d% */ {
			echo 'item';
		}
		echo '"></span>

<span ';
		echo LR\Filters::escapeHtmlTag('title') /* pos %d%:%d% */;
		echo '="';
		echo LR\Filters::escapeHtmlAttr($x) /* pos %d%:%d% */;
		echo '"></span>

<span attr="c';
		echo LR\Filters::escapeHtmlAttr($x) /* pos %d%:%d% */;
		echo 'd"></span>

<span onclick="';
		echo LR\Filters::escapeHtmlAttr(LR\Filters::escapeJs($x)) /* pos %d%:%d% */;
		echo '" ';
		echo LR\Filters::escapeHtmlTag($x) /* pos %d%:%d% */;
		echo '></span>

<span onclick="c';
		echo LR\Filters::escapeHtmlAttr(LR\Filters::escapeJs($x)) /* pos %d%:%d% */;
		echo 'd"></span>

<span attr';
		echo LR\Filters::escapeHtmlTag($x) /* pos %d%:%d% */;
		echo 'b="c';
		echo LR\Filters::escapeHtmlAttr($x) /* pos %d%:%d% */;
		echo 'd"></span>
';
	}
}
