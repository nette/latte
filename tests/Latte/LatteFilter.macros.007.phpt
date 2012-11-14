<?php

/**
 * Test: Nette\Templates\LatteFilter and macros test.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette\Templates
 * @subpackage UnitTests
 */

/*use Nette\Environment;*/
/*use Nette\Templates\Template;*/
/*use Nette\Templates\LatteFilter;*/



require dirname(__FILE__) . '/../NetteTest/initialize.php';

require dirname(__FILE__) . '/Template.inc';



class MockTexy
{
	function process($text, $singleLine = FALSE)
	{
		return '<pre>' . $text . '</pre>';
	}
}



$template = new MockTemplate;
$template->registerFilter(new LatteFilter);
$template->registerHelper('texy', array(new MockTexy, 'process'));
$template->registerHelperLoader('Nette\Templates\TemplateHelpers::loader');

$template->hello = '<i>Hello</i>';
$template->people = array('John', 'Mary', 'Paul');

$template->render(NetteTestHelpers::getSection(__FILE__, 'template'));



__halt_compiler();

-----template-----
{block|lower|texy}
{$hello}
---------
- Escaped: {$hello}
- Non-escaped: {!$hello}

- Escaped expression: {='<' . 'b' . '>hello' . '</b>'}

- Non-escaped expression: {!='<' . 'b' . '>hello' . '</b>'}

- Array access: {$people[1]}

[* image.jpg *]
{/block}

------EXPECT------
<pre>&lt;i&gt;hello&lt;/i&gt;
---------
- escaped: &lt;i&gt;hello&lt;/i&gt;
- non-escaped: <i>hello</i>

- escaped expression: &lt;b&gt;hello&lt;/b&gt;

- non-escaped expression: <b>hello</b>

- array access: mary

[* image.jpg *]
</pre>
