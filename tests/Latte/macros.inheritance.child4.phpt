<?php

/**
 * Test: Nette\Latte\Engine: {extends ...} test IV.
 *
 * @author     David Grudl
 * @package    Nette\Latte
 * @subpackage UnitTests
 * @keepTrailingSpaces
 */

use Nette\Latte,
	Nette\Templating\FileTemplate;



require __DIR__ . '/../initialize.php';

require __DIR__ . '/Template.inc';



$template = new FileTemplate;
$template->setFile(__DIR__ . '/templates/inheritance.child4.latte');
$template->registerFilter(new Latte\Engine);

Assert::match(<<<EOD
	Content
EOD
, $template->__toString(TRUE));
