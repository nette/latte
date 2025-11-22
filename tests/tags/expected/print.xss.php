%A%
		echo LR\Filters::escapeHtmlText($el) /* pos %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($el2) /* pos %d%:%d% */;
		echo '

<p val="';
		echo LR\Filters::escapeHtmlAttr($xss) /* pos %d%:%d% */;
		echo '" > </p>
<p onclick="';
		echo LR\Filters::escapeHtmlAttr(LR\Filters::escapeJs($xss)) /* pos %d%:%d% */;
		echo '"> </p>
<p ONCLICK="';
		echo LR\Filters::escapeHtmlAttr(LR\Filters::escapeJs($xss)) /* pos %d%:%d% */;
		echo '" ';
		echo LR\Filters::escapeHtmlTag($xss) /* pos %d%:%d% */;
		echo '> </p>

<STYLE type="text/css">
<!--
#';
		echo LR\Filters::escapeCss($xss) /* pos %d%:%d% */;
		echo ' {
	background: blue;
}
-->
</STYLE>

<script>
<!--
alert(\'</div>\');

var prop = ';
		echo LR\Filters::escapeJs($people) /* pos %d%:%d% */;
		echo ';

document.getElementById(';
		echo LR\Filters::escapeJs($xss) /* pos %d%:%d% */;
		echo ').style.backgroundColor = \'red\';

var html = ';
		echo LR\Filters::escapeJs($el) /* pos %d%:%d% */;
		echo ' || ';
		echo LR\Filters::escapeJs($el2) /* pos %d%:%d% */;
		echo ';
-->
</script>

<SCRIPT>
/* <![CDATA[ */

var prop2 = ';
		echo LR\Filters::escapeJs($people) /* pos %d%:%d% */;
		echo ';

/* ]]> */
</SCRIPT>

<p onclick=\'alert(';
		echo LR\Filters::escapeHtmlAttr(LR\Filters::escapeJs($xss)) /* pos %d%:%d% */;
		echo ');alert("hello");\'
 title=\'';
		echo LR\Filters::escapeHtmlAttr($xss) /* pos %d%:%d% */;
		echo '\'
 STYLE="color:';
		echo LR\Filters::escapeHtmlAttr(LR\Filters::escapeCss($xss)) /* pos %d%:%d% */;
		echo ';"
 rel="';
		echo LR\Filters::escapeHtmlAttr($xss) /* pos %d%:%d% */;
		echo '"
 onblur="alert(';
		echo LR\Filters::escapeHtmlAttr(LR\Filters::escapeJs($xss)) /* pos %d%:%d% */;
		echo ')"
 alt=\'';
		echo LR\Filters::escapeHtmlAttr($el) /* pos %d%:%d% */;
		echo ' ';
		echo LR\Filters::escapeHtmlAttr($el2) /* pos %d%:%d% */;
		echo '\'
 onfocus="alert(';
		echo LR\Filters::escapeHtmlAttr(LR\Filters::escapeJs($el)) /* pos %d%:%d% */;
		echo ')"
>click on me ';
		echo LR\Filters::escapeHtmlText($xss) /* pos %d%:%d% */;
		echo '</p>';
%A%
