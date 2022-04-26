<?php

declare(strict_types=1);

use Latte\Macro;
use Latte\MacroNode;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class TestMacro implements Macro
{
	private $name;


	public function __construct($name)
	{
		$this->name = $name;
	}


	public function initialize()
	{
	}


	public function finalize()
	{
	}


	public function nodeOpened(MacroNode $node)
	{
		$node->openingCode = "<?php '$this->name open' ?>";
		$node->closingCode = "<?php '$this->name close' ?>";
		$node->attrCode = "<?php '$this->name attr' ?>";
	}


	public function nodeClosed(MacroNode $node)
	{
		$node->content = '[' . $node->content . ']';
	}
}


class SkipMacro implements Macro
{
	public function initialize()
	{
	}


	public function finalize()
	{
	}


	public function nodeOpened(MacroNode $node)
	{
		return false;
	}


	public function nodeClosed(MacroNode $node)
	{
	}
}


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

$latte->addMacro('one', new TestMacro('one'));
$latte->addMacro('two', new TestMacro('two'));
$latte->addMacro('three', new TestMacro('three'));
$latte->addMacro('three', new SkipMacro);


Assert::match(<<<'DOC'
	%A%
			'one open';
			echo '[<div';
			'one attr';
			echo '></div>]';
			'one close';
			echo ' ';
	%A%
	DOC
, $latte->compile('<div n:one></div> '));


Assert::match(<<<'DOC'
	%A%
			'three open';
			echo '[';
			'two open';
			echo '[';
			'one open';
			echo '[<div';
			'one attr';
			'two attr';
			'three attr';
			echo '>@</div>]';
			'one close';
			echo ']';
			'two close';
			echo ']';
			'three close';
			echo ' ';
	%A%
	DOC
, $latte->compile('<div n:two n:three n:one>@</div> '));


Assert::match(<<<'DOC'
	%A%
			'two open';
			echo '[';
			'one open';
			echo '[<div>]';
			'one close';
			echo ']';
			'two close';
			echo '@';
			'two open';
			echo '[';
			'one open';
			echo '[</div>]';
			'one close';
			echo ']';
			'two close';
			echo ' ';
	%A%
	DOC
, $latte->compile('<div n:tag-two n:tag-one>@</div> '));


Assert::match(<<<'DOC'
	%A%
			echo '<div';
			'one attr';
			'two attr';
			echo '>';
			'two open';
			echo '[';
			'one open';
			echo '[@]';
			'one close';
			echo ']';
			'two close';
			echo '</div> ';
	%A%
	DOC
, $latte->compile('<div n:inner-two n:inner-one>@</div> '));


Assert::match(<<<'DOC'
	%A%
			'one open';
			echo '[';
			'two open';
			echo '[<div';
			'three attr';
			'one attr';
			echo '>]';
			'two close';
			'three open';
			echo '[@]';
			'three close';
			'two open';
			echo '[</div>]';
			'two close';
			echo ']';
			'one close';
			echo ' ';
	%A%
	DOC
, $latte->compile('<div n:one n:tag-two n:inner-three>@</div> '));
