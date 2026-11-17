<?php declare(strict_types=1);
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
		echo ((is_object($ʟ_val = $x) ? LR\Helpers::convertTrusted($ʟ_val, 'html/tag') : null) ?? LR\HtmlHelpers::escapeTag($ʟ_val)) /* pos %d%:%d% */;
		echo '></span>

<span title="';
		if (true) /* pos %d%:%d% */ {
			echo ((is_object($ʟ_val = $x) ? LR\Helpers::convertTrusted($ʟ_val, 'html/attr') : null) ?? LR\HtmlHelpers::escapeAttr($ʟ_val)) /* pos %d%:%d% */;
		} else /* pos %d%:%d% */ {
			echo 'item';
		}
		echo '"></span>

<span ';
		echo ((is_object($ʟ_val = 'title') ? LR\Helpers::convertTrusted($ʟ_val, 'html/tag') : null) ?? LR\HtmlHelpers::escapeTag($ʟ_val)) /* pos %d%:%d% */;
		echo '="';
		echo ((is_object($ʟ_val = $x) ? LR\Helpers::convertTrusted($ʟ_val, 'html/attr') : null) ?? LR\HtmlHelpers::escapeAttr($ʟ_val)) /* pos %d%:%d% */;
		echo '"></span>

<span attr="c';
		echo ((is_object($ʟ_val = $x) ? LR\Helpers::convertTrusted($ʟ_val, 'html/attr') : null) ?? LR\HtmlHelpers::escapeAttr($ʟ_val)) /* pos %d%:%d% */;
		echo 'd"></span>

<span';
		echo LR\HtmlHelpers::formatJsAttribute(' onclick', $x) /* pos %d%:%d% */;
		echo ' ';
		echo ((is_object($ʟ_val = $x) ? LR\Helpers::convertTrusted($ʟ_val, 'html/tag') : null) ?? LR\HtmlHelpers::escapeTag($ʟ_val)) /* pos %d%:%d% */;
		echo '></span>

<span onclick="c';
		echo ((is_object($ʟ_val = $x) ? LR\Helpers::convertTrusted($ʟ_val, 'html/attr/js') : null) ?? LR\HtmlHelpers::escapeAttr(LR\Helpers::escapeJs($ʟ_val))) /* pos %d%:%d% */;
		echo 'd"></span>

<span attr';
		echo ((is_object($ʟ_val = $x) ? LR\Helpers::convertTrusted($ʟ_val, 'html/tag') : null) ?? LR\HtmlHelpers::escapeTag($ʟ_val)) /* pos %d%:%d% */;
		echo 'b="c';
		echo ((is_object($ʟ_val = $x) ? LR\Helpers::convertTrusted($ʟ_val, 'html/attr') : null) ?? LR\HtmlHelpers::escapeAttr($ʟ_val)) /* pos %d%:%d% */;
		echo 'd"></span>
';
	}
}
