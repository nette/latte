<?php declare(strict_types=1);
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
		echo ((is_object($ʟ_val = $id) ? LR\Helpers::convertTrusted($ʟ_val, 'xml/bogus') : null) ?? LR\XmlHelpers::escapeTag($ʟ_val)) /* pos %d%:%d% */;
		echo '"?>

<script>';
		if (1) /* pos %d%:%d% */ {
			echo '<meta />';
		}
		echo '</script>


<ul>
	<li>Escaped: ';
		echo ((is_object($ʟ_val = $hello) ? LR\Helpers::convertTrusted($ʟ_val, 'xml') : null) ?? LR\XmlHelpers::escapeText($ʟ_val)) /* pos %d%:%d% */;
		echo '</li>
	<li>Non-escaped: ';
		echo $hello /* pos %d%:%d% */;
		echo '</li>
	<li>Escaped expression: ';
		echo ((is_object($ʟ_val = '<' . 'b' . '>hello' . '</b>') ? LR\Helpers::convertTrusted($ʟ_val, 'xml') : null) ?? LR\XmlHelpers::escapeText($ʟ_val)) /* pos %d%:%d% */;
		echo '</li>
	<li>Non-escaped expression: ';
		echo '<' . 'b' . '>hello' . '</b>' /* pos %d%:%d% */;
		echo '</li>
	<li>Array access: ';
		echo ((is_object($ʟ_val = $people[1]) ? LR\Helpers::convertTrusted($ʟ_val, 'xml') : null) ?? LR\XmlHelpers::escapeText($ʟ_val)) /* pos %d%:%d% */;
		echo '</li>
	<li>Html: ';
		echo ((is_object($ʟ_val = $el) ? LR\Helpers::convertTrusted($ʟ_val, 'xml') : null) ?? LR\XmlHelpers::escapeText($ʟ_val)) /* pos %d%:%d% */;
		echo '</li>
</ul>

<style type="text/css">
<!--
#';
		echo ((is_object($ʟ_val = $id) ? LR\Helpers::convertTrusted($ʟ_val, 'xml/comment') : null) ?? LR\HtmlHelpers::escapeComment($ʟ_val)) /* pos %d%:%d% */;
		echo ' {
	background: blue;
}
-->
</style>


<script>
<!--
var html = ';
		echo ((is_object($ʟ_val = $el) ? LR\Helpers::convertTrusted($ʟ_val, 'xml/comment') : null) ?? LR\HtmlHelpers::escapeComment($ʟ_val)) /* pos %d%:%d% */;
		echo ';
-->
</script>


<p onclick=\'alert(';
		echo ((is_object($ʟ_val = $id) ? LR\Helpers::convertTrusted($ʟ_val, 'xml/attr') : null) ?? LR\XmlHelpers::escapeAttr($ʟ_val)) /* pos %d%:%d% */;
		echo ');alert("hello");\'
 title=\'';
		echo ((is_object($ʟ_val = $id) ? LR\Helpers::convertTrusted($ʟ_val, 'xml/attr') : null) ?? LR\XmlHelpers::escapeAttr($ʟ_val)) /* pos %d%:%d% */;
		echo '"\'
 style="color:';
		echo ((is_object($ʟ_val = $id) ? LR\Helpers::convertTrusted($ʟ_val, 'xml/attr') : null) ?? LR\XmlHelpers::escapeAttr($ʟ_val)) /* pos %d%:%d% */;
		echo ';\'"';
		echo LR\XmlHelpers::formatAttribute('
 alt', $el) /* pos %d%:%d% */;
		echo '
 onfocus="alert(';
		echo ((is_object($ʟ_val = $el) ? LR\Helpers::convertTrusted($ʟ_val, 'xml/attr') : null) ?? LR\XmlHelpers::escapeAttr($ʟ_val)) /* pos %d%:%d% */;
		echo ')"
>click on me</p>


<!-- ';
		echo ((is_object($ʟ_val = $comment) ? LR\Helpers::convertTrusted($ʟ_val, 'xml/comment') : null) ?? LR\HtmlHelpers::escapeComment($ʟ_val)) /* pos %d%:%d% */;
		echo ' -->


</ul>


<ul>
';
		foreach ($people as $person) /* pos %d%:%d% */ {
			echo '	<li>';
			echo ((is_object($ʟ_val = $person) ? LR\Helpers::convertTrusted($ʟ_val, 'xml') : null) ?? LR\XmlHelpers::escapeText($ʟ_val)) /* pos %d%:%d% */;
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

<p';
		echo LR\XmlHelpers::formatAttribute(' val', $xss) /* pos %d%:%d% */;
		echo ' > </p>

<p';
		echo LR\XmlHelpers::formatAttribute(' onclick', $xss) /* pos %d%:%d% */;
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
			header('Content-Type: application/xml; charset=utf-8') /* pos %d%:%d% */;
		}
		return get_defined_vars();
	}
}
