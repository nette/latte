<?php

/**
 * Test: Nette\Latte\Parser::expandMacro() and reentrant.
 *
 * @author     David Grudl
 * @package    Nette\Latte
 * @subpackage UnitTests
 */

use Nette\Latte;



require __DIR__ . '/../bootstrap.php';


$parser = new Latte\Parser;
$set = new Latte\Macros\MacroSet($parser);
$set->addMacro('test', 'echo %node.word', 'echo %node.word');

list($node, $open) = $parser->expandMacro('test', 'first second', '');
Assert::same( '<?php echo "first" ?>',  $open );
Assert::same( '<?php echo "first" ?>',  $node->close('') );
