%A%
		echo ((is_object($ʟ_val = $el) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos %d%:%d% */;
		echo "\n";
		echo ((is_object($ʟ_val = $el2) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos %d%:%d% */;
		echo '

<p';
		echo LR\HtmlHelpers::formatAttribute(' val', $xss) /* pos %d%:%d% */;
		echo ' > </p>
<p';
		echo LR\HtmlHelpers::formatJsAttribute(' onclick', $xss) /* pos %d%:%d% */;
		echo '> </p>
<p';
		echo LR\HtmlHelpers::formatJsAttribute(' ONCLICK', $xss) /* pos %d%:%d% */;
		echo ' ';
		echo ((is_object($ʟ_val = $xss) ? LR\Helpers::convertTrusted($ʟ_val, 'html/tag') : null) ?? LR\HtmlHelpers::escapeTag($ʟ_val)) /* pos %d%:%d% */;
		echo '> </p>

<STYLE type="text/css">
<!--
#';
		echo ((is_object($ʟ_val = $xss) ? LR\Helpers::convertTrusted($ʟ_val, 'html/raw/css') : null) ?? LR\Helpers::escapeCss($ʟ_val)) /* pos %d%:%d% */;
		echo ' {
	background: blue;
}
-->
</STYLE>

<script>
<!--
alert(\'</div>\');

var prop = ';
		echo ((is_object($ʟ_val = $people) ? LR\Helpers::convertTrusted($ʟ_val, 'html/raw/js') : null) ?? LR\Helpers::escapeJs($ʟ_val)) /* pos %d%:%d% */;
		echo ';

document.getElementById(';
		echo ((is_object($ʟ_val = $xss) ? LR\Helpers::convertTrusted($ʟ_val, 'html/raw/js') : null) ?? LR\Helpers::escapeJs($ʟ_val)) /* pos %d%:%d% */;
		echo ').style.backgroundColor = \'red\';

var html = ';
		echo ((is_object($ʟ_val = $el) ? LR\Helpers::convertTrusted($ʟ_val, 'html/raw/js') : null) ?? LR\Helpers::escapeJs($ʟ_val)) /* pos %d%:%d% */;
		echo ' || ';
		echo ((is_object($ʟ_val = $el2) ? LR\Helpers::convertTrusted($ʟ_val, 'html/raw/js') : null) ?? LR\Helpers::escapeJs($ʟ_val)) /* pos %d%:%d% */;
		echo ';
-->
</script>

<SCRIPT>
/* <![CDATA[ */

var prop2 = ';
		echo ((is_object($ʟ_val = $people) ? LR\Helpers::convertTrusted($ʟ_val, 'html/raw/js') : null) ?? LR\Helpers::escapeJs($ʟ_val)) /* pos %d%:%d% */;
		echo ';

/* ]]> */
</SCRIPT>

<p onclick=\'alert(';
		echo ((is_object($ʟ_val = $xss) ? LR\Helpers::convertTrusted($ʟ_val, 'html/attr/js') : null) ?? LR\HtmlHelpers::escapeAttr(LR\Helpers::escapeJs($ʟ_val))) /* pos %d%:%d% */;
		echo ');alert("hello");\'';
		echo LR\HtmlHelpers::formatAttribute('
 title', $xss) /* pos %d%:%d% */;
		echo '
 STYLE="color:';
		echo ((is_object($ʟ_val = $xss) ? LR\Helpers::convertTrusted($ʟ_val, 'html/attr/css') : null) ?? LR\HtmlHelpers::escapeAttr(LR\Helpers::escapeCss($ʟ_val))) /* pos %d%:%d% */;
		echo ';"';
		echo LR\HtmlHelpers::formatListAttribute('
 rel', $xss) /* pos %d%:%d% */;
		echo '
 onblur="alert(';
		echo ((is_object($ʟ_val = $xss) ? LR\Helpers::convertTrusted($ʟ_val, 'html/attr/js') : null) ?? LR\HtmlHelpers::escapeAttr(LR\Helpers::escapeJs($ʟ_val))) /* pos %d%:%d% */;
		echo ')"
 alt=\'';
		echo ((is_object($ʟ_val = $el) ? LR\Helpers::convertTrusted($ʟ_val, 'html/attr') : null) ?? LR\HtmlHelpers::escapeAttr($ʟ_val)) /* pos %d%:%d% */;
		echo ' ';
		echo ((is_object($ʟ_val = $el2) ? LR\Helpers::convertTrusted($ʟ_val, 'html/attr') : null) ?? LR\HtmlHelpers::escapeAttr($ʟ_val)) /* pos %d%:%d% */;
		echo '\'
 onfocus="alert(';
		echo ((is_object($ʟ_val = $el) ? LR\Helpers::convertTrusted($ʟ_val, 'html/attr/js') : null) ?? LR\HtmlHelpers::escapeAttr(LR\Helpers::escapeJs($ʟ_val))) /* pos %d%:%d% */;
		echo ')"
>click on me ';
		echo ((is_object($ʟ_val = $xss) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos %d%:%d% */;
		echo '</p>';
%A%
