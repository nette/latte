<?php

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


class TemplateParams
{
	public $a = 123;
	protected $protected = 'x';
	private $private = 'x';


	/** @function */
	public function myFunc($a)
	{
		return "*$a*";
	}


	/** @filter */
	public function myFilter($a)
	{
		return "%$a%";
	}


	/** @function @filter */
	public function both($a)
	{
		return "#$a#";
	}
}


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);
$latte->setTempDirectory(getTempDir());

Assert::same(
	'%*123*% ##123## ',
	$latte->renderToString('{myFunc($a)|myFilter} {both(123|both)} {if isset($protected) || isset($private)}invisible{/if}', new TemplateParams)
);


ob_start();
$latte->render('{myFunc($a)|myFilter} {both(123|both)} {if isset($protected) || isset($private)}invisible{/if}', new TemplateParams);
Assert::same(
	'%*123*% ##123## ',
	ob_get_clean()
);
