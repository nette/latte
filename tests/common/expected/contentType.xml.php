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
		echo LR\XmlHelpers::escapeTag($id) /* line %d%:%d% */;
		echo '"?>

<script>';
		if (1) /* line %d%:%d% */ {
			echo '<meta />';
		}
		echo '</script>


<ul>
	<li>Escaped: ';
		echo LR\XmlHelpers::escapeText($hello) /* line %d%:%d% */;
		echo '</li>
	<li>Non-escaped: ';
		echo $hello /* line %d%:%d% */;
		echo '</li>
	<li>Escaped expression: ';
		echo LR\XmlHelpers::escapeText('<' . 'b' . '>hello' . '</b>') /* line %d%:%d% */;
		echo '</li>
	<li>Non-escaped expression: ';
		echo '<' . 'b' . '>hello' . '</b>' /* line %d%:%d% */;
		echo '</li>
	<li>Array access: ';
		echo LR\XmlHelpers::escapeText($people[1]) /* line %d%:%d% */;
		echo '</li>
	<li>Html: ';
		echo LR\XmlHelpers::escapeText($el) /* line %d%:%d% */;
		echo '</li>
</ul>

<style type="text/css">
<!--
#';
		echo LR\HtmlHelpers::escapeComment($id) /* line %d%:%d% */;
		echo ' {
	background: blue;
}
-->
</style>


<script>
<!--
var html = ';
		echo LR\HtmlHelpers::escapeComment($el) /* line %d%:%d% */;
		echo ';
-->
</script>


<p onclick=\'alert(';
		echo LR\XmlHelpers::escapeAttr($id) /* line %d%:%d% */;
		echo ');alert("hello");\'
 title=\'';
		echo LR\XmlHelpers::escapeAttr($id) /* line %d%:%d% */;
		echo '"\'
 style="color:';
		echo LR\XmlHelpers::escapeAttr($id) /* line %d%:%d% */;
		echo ';\'"
 alt="';
		echo LR\XmlHelpers::escapeAttr($el) /* line %d%:%d% */;
		echo '"
 onfocus="alert(';
		echo LR\XmlHelpers::escapeAttr($el) /* line %d%:%d% */;
		echo ')"
>click on me</p>


<!-- ';
		echo LR\HtmlHelpers::escapeComment($comment) /* line %d%:%d% */;
		echo ' -->


</ul>


<ul>
';
		foreach ($people as $person) /* line %d%:%d% */ {
			echo '	<li>';
			echo LR\XmlHelpers::escapeText($person) /* line %d%:%d% */;
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

<p val="';
		echo LR\XmlHelpers::escapeAttr($xss) /* line %d%:%d% */;
		echo '" > </p>

<p onclick="';
		echo LR\XmlHelpers::escapeAttr($xss) /* line %d%:%d% */;
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
			header('Content-Type: application/xml; charset=utf-8') /* line %d%:%d% */;
		}
		return get_defined_vars();
	}
}
