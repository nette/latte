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
%A%'one open' ?>[<div<?php 'one attr' ?>></div>]<?php 'one close' %A%
DOC
, $latte->compile('<div n:one></div> '));


Assert::match(<<<'DOC'
%A%
		'three open' ?>[<?php 'two open' ?>[<?php 'one open' ?>[<div<?php
		'one attr';
		'two attr';
		'three attr' ?>>@</div>]<?php 'one close' ?>]<?php 'two close' ?>]<?php 'three close' %A%
DOC
, $latte->compile('<div n:two n:three n:one>@</div> '));


Assert::match(<<<'DOC'
%A%'two open' ?>[<?php 'one open' ?>[<div>]<?php 'one close' ?>]<?php 'two close' ?>@<?php 'two open' ?>[<?php
		'one open' ?>[</div>]<?php 'one close' ?>]<?php 'two close' %A%
DOC
, $latte->compile('<div n:tag-two n:tag-one>@</div> '));


Assert::match(<<<'DOC'
%A%
		'one attr';
		'two attr' ?>><?php 'two open' ?>[<?php 'one open' ?>[@]<?php 'one close' ?>]<?php 'two close' ?></div>%A%
DOC
, $latte->compile('<div n:inner-two n:inner-one>@</div> '));


Assert::match(<<<'DOC'
%A%
		'one open' ?>[<?php 'two open' ?>[<div<?php
		'three attr';
		'one attr' ?>>]<?php
		'two close';
		'three open' ?>[@]<?php
		'three close';
		'two open' ?>[</div>]<?php 'two close' ?>]<?php 'one close' %A%
DOC
, $latte->compile('<div n:one n:tag-two n:inner-three>@</div> '));
