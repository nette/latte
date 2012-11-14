<?php

/**
 * Test: Nette\Latte\Engine: errors.
 *
 * @author     David Grudl
 * @package    Nette\Latte
 * @subpackage UnitTests
 */

use Nette\Latte;



require __DIR__ . '/../bootstrap.php';


$template = new Nette\Templating\Template;
$template->registerFilter(new Latte\Engine);

Assert::throws(function() use ($template) {
	$template->setSource('<a n:href n:href>')->compile();
}, 'Nette\Latte\CompileException', 'Found multiple macro-attributes n:href.');
