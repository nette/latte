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



class MockControl
{

	public function link($destination, $args = array())
	{
		if (!is_array($args)) {
			$args = func_get_args();
			array_shift($args);
		}
		array_unshift($args, $destination);
		return 'LINK(' . implode(', ', $args) . ')';
	}

}



class MockPresenter extends MockControl
{

	public function link($destination, $args = array())
	{
		if (!is_array($args)) {
			$args = func_get_args();
			array_shift($args);
		}
		array_unshift($args, $destination);
		return 'PLINK(' . implode(', ', $args) . ')';
	}

	public function isAjax() {
		return FALSE;
	}

}



$template = new Nette\Templating\Template;
$template->registerFilter(new Latte\Engine);

$template->control = new MockControl;
$template->presenter = new MockPresenter;
$template->action = 'login';
$template->arr = array('link' => 'login', 'param' => 123);

Assert::match(<<<EOD
PLINK(Homepage:)

PLINK(Homepage:)

PLINK(Homepage:action)

PLINK(Homepage:action)

PLINK(Homepage:action, 10, 20, {one}&amp;two)

PLINK(:, 10)

PLINK(default, 10, 20, 30)

LINK(login)

PLINK(login, 123)

LINK(default, 10, 20, 30)
EOD

, $template->__toString(<<<EOD
{plink Homepage:}

{plink  Homepage: }

{plink Homepage:action }

{plink 'Homepage:action' }

{plink Homepage:action 10, 20, '{one}&two'}

{plink : 10 }

{plink default 10, 'a' => 20, 'b' => 30}

{link  \$action}

{plink \$arr['link'], \$arr['param']}

{link default 10, 'a' => 20, 'b' => 30}
EOD
));
