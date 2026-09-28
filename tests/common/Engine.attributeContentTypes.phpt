<?php declare(strict_types=1);

/**
 * Test: Engine validates attribute content types and includes them in the configuration signature.
 */

use Latte\ContentType;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class AttributeContentTypesExtension extends Latte\Extension
{
	public function __construct(
		private array $contentTypes,
	) {
	}


	public function getAttributeContentTypes(): array
	{
		return $this->contentTypes;
	}
}


function createWith(array $contentTypes): Latte\Engine
{
	$latte = createLatte();
	$latte->addExtension(new AttributeContentTypesExtension($contentTypes));
	return $latte;
}


function compileWith(array $contentTypes): string
{
	return createWith($contentTypes)->compile('<a data-x="a{$x}">');
}


Assert::exception(
	fn() => createWith(['data-x' => ContentType::Text])->getTemplateClass('a'),
	LogicException::class,
	"Invalid content type of attribute 'data-x', expected ContentType::JavaScript, Css or Html.",
);

Assert::exception(
	fn() => createWith(['data x' => ContentType::JavaScript])->getTemplateClass('a'),
	LogicException::class,
	"Invalid attribute name 'data x'.",
);

Assert::exception(
	fn() => createWith(['x-*-click' => ContentType::JavaScript])->getTemplateClass('a'),
	LogicException::class,
	"Invalid attribute name 'x-*-click'.",
);

foreach (['onfoo', 'ONCLICK', 'style', 'srcdoc', 'on*', 'o*', 's*', 'st*', 'srcdoc*', 'onclick-*', '*'] as $name) {
	Assert::exception(
		fn() => createWith([$name => ContentType::JavaScript])->getTemplateClass('a'),
		LogicException::class,
		"Attribute '" . strtolower($name) . "' would override the built-in content type of on*, style or srcdoc.",
	);
}

Assert::contains('escapeJs', compileWith(['DATA-X' => ContentType::JavaScript]));
Assert::contains('escapeCss', compileWith(['data-*' => ContentType::Css]));


// last registration wins
$latte = createLatte();
$latte->addExtension(new AttributeContentTypesExtension(['data-x' => ContentType::JavaScript]));
$latte->addExtension(new AttributeContentTypesExtension(['data-x' => ContentType::Css]));
Assert::contains('escapeCss', $latte->compile('<a data-x="a{$x}">'));


// configuration signature
Assert::notSame(
	createWith(['data-x' => ContentType::JavaScript])->getTemplateClass('a'),
	createWith(['data-x' => ContentType::Css])->getTemplateClass('a'),
);


// built-in content types are the default of TemplateGenerator
$generator = new Latte\Compiler\TemplateGenerator;
$generator->buildClass(createLatte()->parse('<a onclick="f({$x})">'));
Assert::contains('escapeJs', $generator->generateCode('Template', 'a', strictMode: false));
