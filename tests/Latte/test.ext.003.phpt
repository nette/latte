<?php

/**
 * Test: Nette\Latte\Engine and macros test.
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



// temporary directory
define('TEMP_DIR', __DIR__ . '/tmp');
TestHelpers::purge(TEMP_DIR);



$template = new FileTemplate;
$template->setCacheStorage(new MockCacheStorage(TEMP_DIR));
$template->setFile(__DIR__ . '/templates/inheritance.child3.latte');
$template->registerFilter(new Latte\Engine);

Assert::match(<<<EOD

	Content
EOD
, $template->__toString(TRUE));
