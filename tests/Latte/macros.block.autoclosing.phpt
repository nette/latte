<?php

/**
 * Test: Nette\Latte\Engine: {block} autoclosing
 *
 * @author     David Grudl
 * @package    Nette\Latte
 * @subpackage UnitTests
 */

use Nette\Latte;



require __DIR__ . '/../initialize.php';

require __DIR__ . '/Template.inc';



$template = new Nette\Templating\Template;
$template->registerFilter(new Latte\Engine);

Assert::match(<<<EOD
Block

EOD

, (string) $template->setSource(<<<EOD
{block}
Block

EOD
));
