<?php declare(strict_types=1);

/** @phpversion 8.4 */

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


$latte = createLatte();
$latte->setCacheDirectory(getTempDir());

Assert::same(
	'x',
	$latte->renderToString('{$public}{if isset($writeOnly)}invisible{/if}', new TemplateParamsWithHooks),
);
