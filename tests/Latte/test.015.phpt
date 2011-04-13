<?php

/**
 * Test: Nette\Latte\Engine and macros test.
 *
 * @author     David Grudl
 * @package    Nette\Latte
 * @subpackage UnitTests
 */

use Nette\Latte;



require __DIR__ . '/../initialize.php';

require __DIR__ . '/Template.inc';


$template = new MockTemplate;
$template->registerFilter(new Latte\Engine);
try {
	$template->render('Block{/block}');
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('Nette\Latte\ParseException', 'Unexpected macro {/block}', $e );
}
