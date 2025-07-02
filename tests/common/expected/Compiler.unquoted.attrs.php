<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{

	public function main(array $ʟ_args): void
	{
%A%
		echo '<span ';
		echo LR\AttributeHandler::formatHtmlAttribute('title', $x) /* line %d%:%d% */;
		echo ' ';
		echo LR\AttributeHandler::formatHtmlAttribute('class', $x) /* line %d%:%d% */;
		echo '></span>

<span ';
		echo LR\AttributeHandler::formatHtmlAttribute('title', $x) /* line %d%:%d% */;
		echo ' ';
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

<span ';
		echo LR\AttributeHandler::formatHtmlAttribute('onclick', $x) /* line %d%:%d% */;
		echo ' ';
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
