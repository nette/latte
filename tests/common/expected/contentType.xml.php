<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{
	public const ContentType = 'xml';

	public const Source = '%a%.latte';


	public function main(array $ʟ_args): void
	{
		extract($ʟ_args);
		unset($ʟ_args);

		echo '<?xml version="1.0" encoding="utf-8"?>
<?xml-stylesheet type="text/css" href="';
		echo LR\Filters::escapeXml($id) /* line %d%:%d% */;
		echo '"?>

<script>';
		if (1) /* line %d%:%d% */ {
			echo '<meta />';
		}
		echo '</script>


<ul>
	<li>Escaped: ';
		echo LR\Filters::escapeXml($hello) /* line %d%:%d% */;
		echo '</li>
	<li>Non-escaped: ';
		echo $hello /* line %d%:%d% */;
		echo '</li>
	<li>Escaped expression: ';
		echo LR\Filters::escapeXml('<' . 'b' . '>hello' . '</b>') /* line %d%:%d% */;
		echo '</li>
	<li>Non-escaped expression: ';
		echo '<' . 'b' . '>hello' . '</b>' /* line %d%:%d% */;
		echo '</li>
	<li>Array access: ';
		echo LR\Filters::escapeXml($people[1]) /* line %d%:%d% */;
		echo '</li>
	<li>Html: ';
		echo LR\Filters::escapeXml($el) /* line %d%:%d% */;
		echo '</li>
</ul>

<style type="text/css">
<!--
#';
		echo LR\Filters::escapeHtmlComment($id) /* line %d%:%d% */;
		echo ' {
	background: blue;
}
-->
</style>


<script>
<!--
var html = ';
		echo LR\Filters::escapeHtmlComment($el) /* line %d%:%d% */;
		echo ';
-->
</script>


<p onclick=\'alert(';
		echo LR\Filters::escapeXml($id) /* line %d%:%d% */;
		echo ');alert("hello");\'
 title=\'';
		echo LR\Filters::escapeXml($id) /* line %d%:%d% */;
		echo '"\'
 style="color:';
		echo LR\Filters::escapeXml($id) /* line %d%:%d% */;
		echo ';\'"
 ';
		echo LR\AttributeHandler::formatXmlAttribute('alt', $el) /* line %d%:%d% */;
		echo '
 onfocus="alert(';
		echo LR\Filters::escapeXml($el) /* line %d%:%d% */;
		echo ')"
>click on me</p>


<!-- ';
		echo LR\Filters::escapeHtmlComment($comment) /* line %d%:%d% */;
		echo ' -->


</ul>


<ul>
';
		foreach ($people as $person) /* line %d%:%d% */ {
			echo '	<li>';
			echo LR\Filters::escapeXml($person) /* line %d%:%d% */;
			echo '</li>
';

		}

		echo '</ul>

';
		if (true) /* line %d%:%d% */ {
			echo '<p>
	<div><p>true</div>
</p>
';
		}
		echo '
<input/> <input />

<p val="';
		if (true) /* line %d%:%d% */ {
			echo 'a';
		} else /* line %d%:%d% */ {
			echo 'b';
		}
		echo '"> </p>

<p ';
		echo LR\AttributeHandler::formatXmlAttribute('val', $xss) /* line %d%:%d% */;
		echo ' > </p>

<p ';
		echo LR\AttributeHandler::formatXmlAttribute('onclick', $xss) /* line %d%:%d% */;
		echo '> </p>
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
			header('Content-Type: application/xml; charset=utf-8') /* line %d%:%d% */;
		}
		return get_defined_vars();
	}
}
