<?php declare(strict_types=1);

/**
 * Test: Engine validates attribute contexts and includes them in the configuration signature.
 */

use Latte\ContentType;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class AttributeContextsExtension extends Latte\Extension
{
	public function __construct(
		private array $contexts,
	) {
	}


	public function getAttributeContexts(): array
	{
		return $this->contexts;
	}
}


function createWith(array $contexts): Latte\Engine
{
	$latte = createLatte();
	$latte->addExtension(new AttributeContextsExtension($contexts));
	return $latte;
}


function compileWith(array $contexts): string
{
	return createWith($contexts)->compile('<a data-x="a{$x}">');
}


Assert::exception(
	fn() => createWith(['data-x' => ContentType::Text])->getTemplateClass('a'),
	LogicException::class,
	"Invalid context for attribute 'data-x', expected ContentType::JavaScript, Css or Html.",
);

Assert::exception(
	fn() => createWith(['data x' => ContentType::JavaScript])->getTemplateClass('a'),
	LogicException::class,
	"Invalid attribute name 'data x'.",
);

foreach (['onfoo', 'ONCLICK', 'style', 'srcdoc', 'on*', 'o*', 's*', 'st*', 'srcdoc*', 'onclick-*', '*'] as $name) {
	Assert::exception(
		fn() => createWith([$name => ContentType::JavaScript])->getTemplateClass('a'),
		LogicException::class,
		"Attribute '" . strtolower($name) . "' would override the built-in context of on*, style or srcdoc.",
	);
}

Assert::contains('escapeJs', compileWith(['DATA-X' => ContentType::JavaScript]));
Assert::contains('escapeCss', compileWith(['data-*' => ContentType::Css]));


// last registration wins
$latte = createLatte();
$latte->addExtension(new AttributeContextsExtension(['data-x' => ContentType::JavaScript]));
$latte->addExtension(new AttributeContextsExtension(['data-x' => ContentType::Css]));
Assert::contains('escapeCss', $latte->compile('<a data-x="a{$x}">'));


// configuration signature
Assert::notSame(
	createWith(['data-x' => ContentType::JavaScript])->getTemplateClass('a'),
	createWith(['data-x' => ContentType::Css])->getTemplateClass('a'),
);
