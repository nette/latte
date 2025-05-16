<?php

/** @phpversion >= 8.4 */
// Property hooks

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class TemplateParamsWithHooks
{
	private $private = 'x';
	public $public { get => $this->private; }

	public $writeOnly {
		set {
			$this->private = $value;
		}
	}
}


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);
$latte->setTempDirectory(getTempDir());

Assert::same(
	'x',
	$latte->renderToString('{$public}{if isset($writeOnly)}invisible{/if}', new TemplateParamsWithHooks),
);
