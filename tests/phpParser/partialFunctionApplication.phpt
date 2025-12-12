<?php declare(strict_types=1);

// Partial Function Application

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	foo($a, ?, ...),
	$this->foo($a, ?, ...),
	A::foo($a, ?, ...),
	foo(name: ?),
	clone(?),
	XX;

Assert::exception(
	fn() => parseCode('foo(..., $a)'),
	Latte\CompileException::class,
	'Variadic placeholder must be last (on line 1 at column 5)',
);

Assert::exception(
	fn() => parseCode('clone(..., ?)'),
	Latte\CompileException::class,
	'Variadic placeholder must be last (on line 1 at column 7)',
);

if (PHP_VERSION_ID < 80600) {
	Assert::exception(
		fn() => parseCode('foo(?)'),
		Latte\CompileException::class,
		'Partial function application requires PHP 8.6 (on line 1 at column 5)',
	);

	Assert::exception(
		fn() => parseCode('foo($a, ...)'),
		Latte\CompileException::class,
		'Partial function application requires PHP 8.6 (on line 1 at column 9)',
	);
	exit;
}

Assert::exception(
	fn() => parseCode('foo(?, ...$a)'),
	Latte\CompileException::class,
	'Cannot combine partial application and unpacking (on line 1 at column 8)',
);

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();
Latte\Compiler\Nodes\Php\Expression\ArrayNode
   items: array (5)
   |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  name: 'foo'
   |  |  |  |  kind: 1
   |  |  |  |  position: 1:1
   |  |  |  |  end: 1:4
   |  |  |  args: array (3)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 1:5
   |  |  |  |  |  |  end: 1:7
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 1:5
   |  |  |  |  |  end: 1:7
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArgumentPlaceholderNode
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 1:9
   |  |  |  |  |  end: 1:10
   |  |  |  |  2 => Latte\Compiler\Nodes\Php\VariadicPlaceholderNode
   |  |  |  |  |  position: 1:12
   |  |  |  |  |  end: 1:15
   |  |  |  position: 1:1
   |  |  |  end: 1:16
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1
   |  |  end: 1:16
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\MethodCallNode
   |  |  |  object: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'this'
   |  |  |  |  position: 2:1
   |  |  |  |  end: 2:6
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'foo'
   |  |  |  |  position: 2:8
   |  |  |  |  end: 2:11
   |  |  |  args: array (3)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 2:12
   |  |  |  |  |  |  end: 2:14
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 2:12
   |  |  |  |  |  end: 2:14
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArgumentPlaceholderNode
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 2:16
   |  |  |  |  |  end: 2:17
   |  |  |  |  2 => Latte\Compiler\Nodes\Php\VariadicPlaceholderNode
   |  |  |  |  |  position: 2:19
   |  |  |  |  |  end: 2:22
   |  |  |  nullsafe: false
   |  |  |  position: 2:1
   |  |  |  end: 2:23
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1
   |  |  end: 2:23
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\StaticMethodCallNode
   |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  name: 'A'
   |  |  |  |  kind: 1
   |  |  |  |  position: 3:1
   |  |  |  |  end: 3:2
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'foo'
   |  |  |  |  position: 3:4
   |  |  |  |  end: 3:7
   |  |  |  args: array (3)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 3:8
   |  |  |  |  |  |  end: 3:10
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 3:8
   |  |  |  |  |  end: 3:10
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArgumentPlaceholderNode
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 3:12
   |  |  |  |  |  end: 3:13
   |  |  |  |  2 => Latte\Compiler\Nodes\Php\VariadicPlaceholderNode
   |  |  |  |  |  position: 3:15
   |  |  |  |  |  end: 3:18
   |  |  |  position: 3:1
   |  |  |  end: 3:19
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 3:1
   |  |  end: 3:19
   |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  name: 'foo'
   |  |  |  |  kind: 1
   |  |  |  |  position: 4:1
   |  |  |  |  end: 4:4
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentPlaceholderNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'name'
   |  |  |  |  |  |  position: 4:5
   |  |  |  |  |  |  end: 4:9
   |  |  |  |  |  position: 4:5
   |  |  |  |  |  end: 4:12
   |  |  |  position: 4:1
   |  |  |  end: 4:13
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 4:1
   |  |  end: 4:13
   |  4 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  name: 'clone'
   |  |  |  |  kind: 1
   |  |  |  |  position: 5:1
   |  |  |  |  end: 5:9
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentPlaceholderNode
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 5:7
   |  |  |  |  |  end: 5:8
   |  |  |  position: 5:1
   |  |  |  end: 5:9
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 5:1
   |  |  end: 5:9
   position: 1:1
   end: 5:10
