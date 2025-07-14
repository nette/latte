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
		echo LR\XmlHelpers::escapeTag($id) /* line %d% */;
		echo '"?>

<script>';
		if (1) /* line %d% */ {
			echo '<meta />';
		}
		echo '</script>


<ul>
	<li>Escaped: ';
		echo LR\XmlHelpers::escapeText($hello) /* line %d% */;
		echo '</li>
	<li>Non-escaped: ';
		echo $hello /* line %d% */;
		echo '</li>
	<li>Escaped expression: ';
		echo LR\XmlHelpers::escapeText('<' . 'b' . '>hello' . '</b>') /* line %d% */;
		echo '</li>
	<li>Non-escaped expression: ';
		echo '<' . 'b' . '>hello' . '</b>' /* line %d% */;
		echo '</li>
	<li>Array access: ';
		echo LR\XmlHelpers::escapeText($people[1]) /* line %d% */;
		echo '</li>
	<li>Html: ';
		echo LR\XmlHelpers::escapeText($el) /* line %d% */;
		echo '</li>
</ul>

<style type="text/css">
<!--
#';
		echo LR\HtmlHelpers::escapeComment($id) /* line %d% */;
		echo ' {
	background: blue;
}
-->
</style>


<script>
<!--
var html = ';
		echo LR\HtmlHelpers::escapeComment($el) /* line %d% */;
		echo ';
-->
</script>


<p onclick=\'alert(';
		echo LR\XmlHelpers::escapeText($id) /* line %d% */;
		echo ');alert("hello");\'
 title=\'';
		echo LR\XmlHelpers::escapeText($id) /* line %d% */;
		echo '"\'
 style="color:';
		echo LR\XmlHelpers::escapeText($id) /* line %d% */;
		echo ';\'"
 alt=\'';
		echo LR\XmlHelpers::escapeText($el) /* line %d% */;
		echo '\'
 onfocus="alert(';
		echo LR\XmlHelpers::escapeText($el) /* line %d% */;
		echo ')"
>click on me</p>


<!-- ';
		echo LR\HtmlHelpers::escapeComment($comment) /* line %d% */;
		echo ' -->


</ul>


<ul>
';
		foreach ($people as $person) /* line %d% */ {
			echo '	<li>';
			echo LR\XmlHelpers::escapeText($person) /* line %d% */;
			echo '</li>
';

		}

		echo '</ul>

';
		if (true) /* line %d% */ {
			echo '<p>
	<div><p>true</div>
</p>
';
		}
		echo '
<input/> <input />

<p val="';
		if (true) /* line %d% */ {
			echo 'a';
		} else /* line %d% */ {
			echo 'b';
		}
		echo '"> </p>

<p val="';
		echo LR\XmlHelpers::escapeText($xss) /* line %d% */;
		echo '" > </p>

<p onclick="';
		echo LR\XmlHelpers::escapeText($xss) /* line %d% */;
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
			header('Content-Type: application/xml; charset=utf-8') /* line %d% */;
		}
		return get_defined_vars();
	}
}
