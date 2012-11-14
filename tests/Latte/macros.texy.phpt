<?php

/**
 * Test: Nette\Latte\Engine and Texy.
 *
 * @author     David Grudl
 * @package    Nette\Latte
 * @subpackage UnitTests
 */

use Nette\Latte;



require __DIR__ . '/../initialize.php';

require __DIR__ . '/Template.inc';



class MockTexy
{
	function process($text, $singleLine = FALSE)
	{
		return '<pre>' . $text . '</pre>';
	}
}



$template = new Nette\Templating\Template;
$template->registerFilter(new Latte\Engine);
$template->registerHelper('texy', array(new MockTexy, 'process'));
$template->registerHelperLoader('Nette\Templating\DefaultHelpers::loader');

$template->hello = '<i>Hello</i>';
$template->people = array('John', 'Mary', 'Paul');

$result = $template->__toString(<<<EOD
{block|lower|texy}
{\$hello}
---------
- Escaped: {\$hello}
- Non-escaped: {!\$hello}

- Escaped expression: {="<" . "b" . ">hello" . "</b>"}

- Non-escaped expression: {!="<" . "b" . ">hello" . "</b>"}

- Array access: {\$people[1]}

[* image.jpg *]
{/block}
EOD
);

Assert::match(<<<EOD
<pre>&lt;i&gt;hello&lt;/i&gt;
---------
- escaped: &lt;i&gt;hello&lt;/i&gt;
- non-escaped: <i>hello</i>

- escaped expression: &lt;b&gt;hello&lt;/b&gt;

- non-escaped expression: <b>hello</b>

- array access: mary

[* image.jpg *]
</pre>
EOD
, $result);
