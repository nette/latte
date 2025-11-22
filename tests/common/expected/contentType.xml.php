<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{
	public const ContentType = 'xml';


	public function main(array $ʟ_args): void
	{
		extract($ʟ_args);
		unset($ʟ_args);

		echo '<?xml version="1.0" encoding="utf-8"?>
<?xml-stylesheet type="text/css" href="';
		echo LR\Filters::escapeXmlText($id) /* pos %d%:%d% */;
		echo '"?>

<script>';
		if (1) /* pos %d%:%d% */ {
			echo '<meta />';
		}
		echo '</script>


<ul>
	<li>Escaped: ';
		echo LR\Filters::escapeXmlText($hello) /* pos %d%:%d% */;
		echo '</li>
	<li>Non-escaped: ';
		echo $hello /* pos %d%:%d% */;
		echo '</li>
	<li>Escaped expression: ';
		echo LR\Filters::escapeXmlText('<' . 'b' . '>hello' . '</b>') /* pos %d%:%d% */;
		echo '</li>
	<li>Non-escaped expression: ';
		echo '<' . 'b' . '>hello' . '</b>' /* pos %d%:%d% */;
		echo '</li>
	<li>Array access: ';
		echo LR\Filters::escapeXmlText($people[1]) /* pos %d%:%d% */;
		echo '</li>
	<li>Html: ';
		echo LR\Filters::escapeXmlText($el) /* pos %d%:%d% */;
		echo '</li>
</ul>

<style type="text/css">
<!--
#';
		echo LR\Filters::escapeHtmlComment($id) /* pos %d%:%d% */;
		echo ' {
	background: blue;
}
-->
</style>


<script>
<!--
var html = ';
		echo LR\Filters::escapeHtmlComment($el) /* pos %d%:%d% */;
		echo ';
-->
</script>


<p onclick=\'alert(';
		echo LR\Filters::escapeXmlAttr($id) /* pos %d%:%d% */;
		echo ');alert("hello");\'
 title=\'';
		echo LR\Filters::escapeXmlAttr($id) /* pos %d%:%d% */;
		echo '"\'
 style="color:';
		echo LR\Filters::escapeXmlAttr($id) /* pos %d%:%d% */;
		echo ';\'"
 alt=\'';
		echo LR\Filters::escapeXmlAttr($el) /* pos %d%:%d% */;
		echo '\'
 onfocus="alert(';
		echo LR\Filters::escapeXmlAttr($el) /* pos %d%:%d% */;
		echo ')"
>click on me</p>


<!-- ';
		echo LR\Filters::escapeHtmlComment($comment) /* pos %d%:%d% */;
		echo ' -->


</ul>


<ul>
';
		foreach ($people as $person) /* pos %d%:%d% */ {
			echo '	<li>';
			echo LR\Filters::escapeXmlText($person) /* pos %d%:%d% */;
			echo '</li>
';

		}

		echo '</ul>

';
		if (true) /* pos %d%:%d% */ {
			echo '<p>
	<div><p>true</div>
</p>
';
		}
		echo '
<input/> <input />

<p val="';
		if (true) /* pos %d%:%d% */ {
			echo 'a';
		} else /* pos %d%:%d% */ {
			echo 'b';
		}
		echo '"> </p>

<p val="';
		echo LR\Filters::escapeXmlAttr($xss) /* pos %d%:%d% */;
		echo '" > </p>

<p onclick="';
		echo LR\Filters::escapeXmlAttr($xss) /* pos %d%:%d% */;
		echo '"> </p>
';
	}


	public function prepare(): array
	{
		extract($this->params);

		if (!$this->getReferringTemplate() || $this->getReferenceType() === 'extends') {
			foreach (array_intersect_key(['person' => '50'], $this->params) as $ʟ_v => $ʟ_l) {
				trigger_error("Variable \$$ʟ_v overwritten in foreach on line $ʟ_l");
			}
		}
		if (empty($this->global->coreCaptured) && in_array($this->getReferenceType(), ['extends', null], true)) {
			header('Content-Type: application/xml; charset=utf-8') /* pos %d%:%d% */;
		}
		return get_defined_vars();
	}
}
