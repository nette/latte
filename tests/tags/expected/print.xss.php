%A%
		echo LR\Filters::escapeHtmlText($el) /* line %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($el2) /* line %d%:%d% */;
		echo '

<p ';
		echo LR\AttributeHandler::formatHtmlAttribute('val', $xss) /* line %d%:%d% */;
		echo ' > </p>
<p ';
		echo LR\AttributeHandler::formatHtmlAttribute('onclick', $xss) /* line %d%:%d% */;
		echo '> </p>
<p ';
		echo LR\AttributeHandler::formatHtmlAttribute('ONCLICK', $xss) /* line %d%:%d% */;
		echo ' ';
		echo LR\Filters::escapeHtmlTag($xss) /* line %d%:%d% */;
		echo '> </p>

<STYLE type="text/css">
<!--
#';
		echo LR\Filters::escapeCss($xss) /* line %d%:%d% */;
		echo ' {
	background: blue;
}
-->
</STYLE>

<script>
<!--
alert(\'</div>\');

var prop = ';
		echo LR\Filters::escapeJs($people) /* line %d%:%d% */;
		echo ';

document.getElementById(';
		echo LR\Filters::escapeJs($xss) /* line %d%:%d% */;
		echo ').style.backgroundColor = \'red\';

var html = ';
		echo LR\Filters::escapeJs($el) /* line %d%:%d% */;
		echo ' || ';
		echo LR\Filters::escapeJs($el2) /* line %d%:%d% */;
		echo ';
-->
</script>

<SCRIPT>
/* <![CDATA[ */

var prop2 = ';
		echo LR\Filters::escapeJs($people) /* line %d%:%d% */;
		echo ';

/* ]]> */
</SCRIPT>

<p onclick=\'alert(';
		echo LR\Filters::escapeHtmlAttr(LR\Filters::escapeJs($xss)) /* line %d%:%d% */;
		echo ');alert("hello");\'
 ';
		echo LR\AttributeHandler::formatHtmlAttribute('title', $xss) /* line %d%:%d% */;
		echo '
 STYLE="color:';
		echo LR\Filters::escapeHtmlAttr(LR\Filters::escapeCss($xss)) /* line %d%:%d% */;
		echo ';"
 ';
		echo LR\AttributeHandler::formatHtmlAttribute('rel', $xss) /* line %d%:%d% */;
		echo '
 onblur="alert(';
		echo LR\Filters::escapeHtmlAttr(LR\Filters::escapeJs($xss)) /* line %d%:%d% */;
		echo ')"
 alt=\'';
		echo LR\Filters::escapeHtmlAttr($el) /* line %d%:%d% */;
		echo ' ';
		echo LR\Filters::escapeHtmlAttr($el2) /* line %d%:%d% */;
		echo '\'
 onfocus="alert(';
		echo LR\Filters::escapeHtmlAttr(LR\Filters::escapeJs($el)) /* line %d%:%d% */;
		echo ')"
>click on me ';
		echo LR\Filters::escapeHtmlText($xss) /* line %d%:%d% */;
		echo '</p>';
%A%
