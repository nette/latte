%A%
		echo LR\HtmlHelpers::escapeText($el) /* pos %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($el2) /* pos %d%:%d% */;
		echo '

<p';
		echo LR\HtmlHelpers::formatAttribute(' val', $xss) /* pos %d%:%d% */;
		echo ' > </p>
<p';
		echo LR\HtmlHelpers::formatAttribute(' onclick', $xss) /* pos %d%:%d% */;
		echo '> </p>
<p';
		echo LR\HtmlHelpers::formatAttribute(' ONCLICK', $xss) /* pos %d%:%d% */;
		echo ' ';
		echo LR\HtmlHelpers::escapeTag($xss) /* pos %d%:%d% */;
		echo '> </p>

<STYLE type="text/css">
<!--
#';
		echo LR\Helpers::escapeCss($xss) /* pos %d%:%d% */;
		echo ' {
	background: blue;
}
-->
</STYLE>

<script>
<!--
alert(\'</div>\');

var prop = ';
		echo LR\Helpers::escapeJs($people) /* pos %d%:%d% */;
		echo ';

document.getElementById(';
		echo LR\Helpers::escapeJs($xss) /* pos %d%:%d% */;
		echo ').style.backgroundColor = \'red\';

var html = ';
		echo LR\Helpers::escapeJs($el) /* pos %d%:%d% */;
		echo ' || ';
		echo LR\Helpers::escapeJs($el2) /* pos %d%:%d% */;
		echo ';
-->
</script>

<SCRIPT>
/* <![CDATA[ */

var prop2 = ';
		echo LR\Helpers::escapeJs($people) /* pos %d%:%d% */;
		echo ';

/* ]]> */
</SCRIPT>

<p onclick=\'alert(';
		echo LR\HtmlHelpers::escapeAttr(LR\Helpers::escapeJs($xss)) /* pos %d%:%d% */;
		echo ');alert("hello");\'';
		echo LR\HtmlHelpers::formatAttribute('
 title', $xss) /* pos %d%:%d% */;
		echo '
 STYLE="color:';
		echo LR\HtmlHelpers::escapeAttr(LR\Helpers::escapeCss($xss)) /* pos %d%:%d% */;
		echo ';"';
		echo LR\HtmlHelpers::formatListAttribute('
 rel', $xss) /* pos %d%:%d% */;
		echo '
 onblur="alert(';
		echo LR\HtmlHelpers::escapeAttr(LR\Helpers::escapeJs($xss)) /* pos %d%:%d% */;
		echo ')"
 alt=\'';
		echo LR\HtmlHelpers::escapeAttr($el) /* pos %d%:%d% */;
		echo ' ';
		echo LR\HtmlHelpers::escapeAttr($el2) /* pos %d%:%d% */;
		echo '\'
 onfocus="alert(';
		echo LR\HtmlHelpers::escapeAttr(LR\Helpers::escapeJs($el)) /* pos %d%:%d% */;
		echo ')"
>click on me ';
		echo LR\HtmlHelpers::escapeText($xss) /* pos %d%:%d% */;
		echo '</p>';
%A%
