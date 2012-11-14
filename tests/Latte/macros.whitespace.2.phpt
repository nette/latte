<?php

/**
 * Test: Nette\Latte\Engine: whitespace test II.
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
qwerty

EOD

, (string) $template->setSource(<<<EOD
{* comment
*}
qwerty

EOD
));



Assert::match(<<<EOD
qwerty

EOD

, (string) $template->setSource(<<<EOD
{* comment
*}

qwerty

EOD
));



Assert::match(<<<EOD

qwerty

EOD

, (string) $template->setSource(<<<EOD
{* comment
*}


qwerty

EOD
));



Assert::match(<<<EOD
qwerty

EOD

, (string) $template->setSource(<<<EOD
{* comment
*}

{contentType text}
qwerty

EOD
));


Assert::match(<<<EOD
qwerty

EOD

, (string) $template->setSource(<<<EOD
{* comment
*}
{contentType text}
qwerty

EOD
));
