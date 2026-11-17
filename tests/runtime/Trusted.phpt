<?php declare(strict_types=1);

/**
 * Test: Latte\Runtime\Trusted* classes
 */

use Latte\Runtime\{Html, HtmlStringable, TrustedCss, TrustedHtml, TrustedJavaScript, TrustedXml};
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$stringable = new class {
	public function __toString(): string
	{
		return 'obj';
	}
};

foreach ([TrustedHtml::class, TrustedJavaScript::class, TrustedCss::class, TrustedXml::class] as $class) {
	Assert::same('x', (string) new $class('x'));
	Assert::same('obj', (string) new $class($stringable));
	Assert::same('', (string) new $class(null));
	Assert::type(Stringable::class, new $class('x'));
	Assert::same($class === TrustedHtml::class, (new $class('x')) instanceof HtmlStringable);
}

Assert::type(TrustedHtml::class, new Html('x'));
Assert::type(Html::class, new TrustedHtml('x'));
