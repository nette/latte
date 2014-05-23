<?php

/**
 * Test: Latte\Runtime\Filters::escapeHtml
 */

use Latte\Runtime\Filters,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';


class Test implements Latte\Runtime\IHtmlString
{
	function __toString()
	{
		return '<br>';
	}
}

Assert::same( '', Filters::escapeHtml(NULL) );
Assert::same( '1', Filters::escapeHtml(1) );
Assert::same( '&lt;br&gt;', Filters::escapeHtml('<br>') );
Assert::same( '&lt; &amp; &#039; &quot; &gt;', Filters::escapeHtml('< & \' " >') );
Assert::same( '&lt; &amp; \' " &gt;', Filters::escapeHtml('< & \' " >', ENT_NOQUOTES) );
Assert::same( '<br>', Filters::escapeHtml(new Test) );
Assert::same( '<br>', Filters::escapeHtml(new Latte\Runtime\Html('<br>')) );
