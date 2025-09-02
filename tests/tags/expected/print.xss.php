%A%
		echo LR\HtmlHelpers::escapeText($el) /* line %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($el2) /* line %d%:%d% */;
		echo '

<p ';
		echo LR\HtmlHelpers::formatAttribute('val', $xss) /* line %d%:%d% */;
		echo ' > </p>
<p ';
		echo LR\HtmlHelpers::formatAttribute('onclick', $xss) /* line %d%:%d% */;
		echo '> </p>
<p ';
		echo LR\HtmlHelpers::formatAttribute('ONCLICK', $xss) /* line %d%:%d% */;
		echo ' ';
		echo LR\HtmlHelpers::escapeTag($xss) /* line %d%:%d% */;
		echo '> </p>

<STYLE type="text/css">
<!--
#';
		echo LR\Helpers::escapeCss($xss) /* line %d%:%d% */;
		echo ' {
	background: blue;
}
-->
</STYLE>

<script>
<!--
alert(\'</div>\');

var prop = ';
		echo LR\Helpers::escapeJs($people) /* line %d%:%d% */;
		echo ';

document.getElementById(';
		echo LR\Helpers::escapeJs($xss) /* line %d%:%d% */;
		echo ').style.backgroundColor = \'red\';

var html = ';
		echo LR\Helpers::escapeJs($el) /* line %d%:%d% */;
		echo ' || ';
		echo LR\Helpers::escapeJs($el2) /* line %d%:%d% */;
		echo ';
-->
</script>

<SCRIPT>
/* <![CDATA[ */

var prop2 = ';
		echo LR\Helpers::escapeJs($people) /* line %d%:%d% */;
		echo ';

/* ]]> */
</SCRIPT>

<p onclick=\'alert(';
		echo LR\HtmlHelpers::escapeAttr(LR\Helpers::escapeJs($xss)) /* line %d%:%d% */;
		echo ');alert("hello");\'
 ';
		echo LR\HtmlHelpers::formatAttribute('title', $xss) /* line %d%:%d% */;
		echo '
 STYLE="color:';
		echo LR\HtmlHelpers::escapeAttr(LR\Helpers::escapeCss($xss)) /* line %d%:%d% */;
		echo ';"
 ';
		echo LR\HtmlHelpers::formatAttribute('rel', $xss) /* line %d%:%d% */;
		echo '
 onblur="alert(';
		echo LR\HtmlHelpers::escapeAttr(LR\Helpers::escapeJs($xss)) /* line %d%:%d% */;
		echo ')"
 alt=\'';
		echo LR\HtmlHelpers::escapeAttr($el) /* line %d%:%d% */;
		echo ' ';
		echo LR\HtmlHelpers::escapeAttr($el2) /* line %d%:%d% */;
		echo '\'
 onfocus="alert(';
		echo LR\HtmlHelpers::escapeAttr(LR\Helpers::escapeJs($el)) /* line %d%:%d% */;
		echo ')"
>click on me ';
		echo LR\HtmlHelpers::escapeText($xss) /* line %d%:%d% */;
		echo '</p>';
%A%
