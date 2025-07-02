%A%
		echo LR\HtmlHelpers::escapeText($el) /* line %d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($el2) /* line %d% */;
		echo '

<p ';
		echo LR\HtmlHelpers::formatAttribute('val', $xss) /* line %d% */;
		echo ' > </p>
<p ';
		echo LR\HtmlHelpers::formatAttribute('onclick', $xss) /* line %d% */;
		echo '> </p>
<p ';
		echo LR\HtmlHelpers::formatAttribute('ONCLICK', $xss) /* line %d% */;
		echo ' ';
		echo LR\HtmlHelpers::escapeTag($xss) /* line %d% */;
		echo '> </p>

<STYLE type="text/css">
<!--
#';
		echo LR\Helpers::escapeCss($xss) /* line %d% */;
		echo ' {
	background: blue;
}
-->
</STYLE>

<script>
<!--
alert(\'</div>\');

var prop = ';
		echo LR\Helpers::escapeJs($people) /* line %d% */;
		echo ';

document.getElementById(';
		echo LR\Helpers::escapeJs($xss) /* line %d% */;
		echo ').style.backgroundColor = \'red\';

var html = ';
		echo LR\Helpers::escapeJs($el) /* line %d% */;
		echo ' || ';
		echo LR\Helpers::escapeJs($el2) /* line %d% */;
		echo ';
-->
</script>

<SCRIPT>
/* <![CDATA[ */

var prop2 = ';
		echo LR\Helpers::escapeJs($people) /* line %d% */;
		echo ';

/* ]]> */
</SCRIPT>

<p onclick=\'alert(';
		echo LR\HtmlHelpers::escapeAttr(LR\Helpers::escapeJs($xss)) /* line %d% */;
		echo ');alert("hello");\'
 ';
		echo LR\HtmlHelpers::formatAttribute('title', $xss) /* line %d% */;
		echo '
 STYLE="color:';
		echo LR\HtmlHelpers::escapeAttr(LR\Helpers::escapeCss($xss)) /* line %d% */;
		echo ';"
 ';
		echo LR\HtmlHelpers::formatAttribute('rel', $xss) /* line %d% */;
		echo '
 onblur="alert(';
		echo LR\HtmlHelpers::escapeAttr(LR\Helpers::escapeJs($xss)) /* line %d% */;
		echo ')"
 alt=\'';
		echo LR\HtmlHelpers::escapeAttr($el) /* line %d% */;
		echo ' ';
		echo LR\HtmlHelpers::escapeAttr($el2) /* line %d% */;
		echo '\'
 onfocus="alert(';
		echo LR\HtmlHelpers::escapeAttr(LR\Helpers::escapeJs($el)) /* line %d% */;
		echo ')"
>click on me ';
		echo LR\HtmlHelpers::escapeText($xss) /* line %d% */;
		echo '</p>';
%A%
