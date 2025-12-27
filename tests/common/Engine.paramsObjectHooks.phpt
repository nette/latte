<?php

/** @phpversion 8.4 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class TemplateParamsWithHooks
{
	public $public { get => $this->private; }

	public $writeOnly {
		set {
			$this->private = $value;
		}
	}
	private $private = 'x';
}


$latte = createLatte();
$latte->setTempDirectory(getTempDir());

Assert::same(
	'x',
	$latte->renderToString('{$public}{if isset($writeOnly)}invisible{/if}', new TemplateParamsWithHooks),
);
