<?php declare(strict_types=1);

/**
 * Test: getIterator() yields every child node by reference.
 */

use Latte\Compiler\{Block, Node};
use Latte\Essential\Nodes\IncludeBlockNode;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


const SkippedProperties = ['parent', 'htmlElement', 'element', 'foreach', 'tagRanges', 'position', 'end'];


/** @return list<Node> */
function collectChildren(Node $node): array
{
	$res = [];
	for ($class = new ReflectionClass($node); $class; $class = $class->getParentClass()) {
		foreach ($class->getProperties() as $prop) {
			if ($prop->getDeclaringClass()->name === $class->name
				&& !$prop->isStatic()
				&& !in_array($prop->name, SkippedProperties, true)
				&& !($node instanceof IncludeBlockNode && $prop->name === 'blocks')
				&& $prop->isInitialized($node)
			) {
				collectNodes($prop->getValue($node), $res);
			}
		}
	}
	return $res;
}


function collectNodes(mixed $value, array &$res): void
{
	if ($value instanceof Node) {
		$res[] = $value;
	} elseif (is_array($value)) {
		foreach ($value as $item) {
			collectNodes($item, $res);
		}
	} elseif ($value instanceof Block) {
		collectNodes($value->name, $res);
		collectNodes($value->parameters, $res);
	}
}


function checkNode(Node $node, string $template): void
{
	$yielded = [];
	foreach ($node as &$child) {
		if (!$child instanceof Node) {
			continue;
		}
		$orig = $child;
		$yielded[] = $orig;
		$child = clone $orig;
		Assert::true(
			in_array($child, collectChildren($node), true),
			$node::class . ' yields ' . $orig::class . ' not by reference in ' . $template,
		);
		$child = $orig;
	}
	unset($child);

	foreach (collectChildren($node) as $child) {
		Assert::true(
			in_array($child, $yielded, true),
			$node::class . ' does not yield ' . $child::class . ' in ' . $template,
		);
	}
}


function checkTree(Node $root, string $template): void
{
	$queue = [$root];
	$visited = [];
	while ($node = array_shift($queue)) {
		if (isset($visited[spl_object_id($node)])) {
			continue;
		}
		$visited[spl_object_id($node)] = true;
		checkNode($node, $template);
		array_push($queue, ...collectChildren($node));
	}
}


$templates = [
	// expressions
	'{=$a + $b * -$c ?? $d ? $e : $f}',
	'{=$a?->b?->c($d)}',
	'{=$obj->$name[$i]->{$x}(...$args)}',
	'{=Foo::$bar}{=Foo::BAR}{=$cls::method(a: 1)}{=Foo::{$m}()}',
	'{=[1, "k" => $v, ...$rest]}{=[$a, [$b]] = $c}',
	'{=fn($x) => $x}{=function ($x) use ($y) { return $x; }}',
	'{=strlen(...)}{=$obj->method(...)}',
	'{=new Foo($a)}{=clone $a}{=isset($a, $b[1])}{=empty($a)}',
	'{=$a instanceof Foo}{=(int) $a}{=!$a}{=$a++}{=--$a}{=$a += 1}{=$a ??= 2}',
	'{=match ($a) { 1, 2 => "x", default => "y" }}',
	'{="a{$b}c"}{=$a in [1, 2]}{=$a |> trim(...)}',
	'{$a|upper|truncate: 10, "..."}{=$a|noescape}',
	'{=$a[1]}{=$a::class}{=\\FOO}{=\\Foo\\bar()}{=$a ?: $b}',

	// tags
	'{extends "layout"}{block a}{/block}',
	'{layout "layout"}',
	'{import "file"}',
	'{include "file", a: 1}{include block a}{include #a}{block a}{include parent}{include this}{/block}',
	'{include block $name . "x" from "file", a: 1}',
	'{embed "file", a: 1}{block a}x{/block}{/embed}',
	'{embed block b}{block a}x{/block}{/embed}{define b}{/define}',
	'{block a}x{/block}{block $name}y{/block}{block "a$i"|upper}z{/block}{block myFunc()}{/block}',
	'{define a, $x, int $y = 1}{$x}{/define}{define $name . "b"}{/define}{define myFunc()}{/define}',
	'<div n:block="$name . x">x</div><div n:block="a">y</div>',
	'{ifset block a}x{/ifset}{ifset $a, $b[1]}y{elseifset $c}{else}z{/ifset}',
	'{parameters int $a = 1, $b}{varType int $c}',
	'{templateType Foo}{varPrint}{templatePrint}',
	'{do $a = 1}{php $b = 2}{var $c = 1, $d = 2}{default $e = 3}',
	'{contentType html}{spaceless}<b> </b>{/spaceless}{capture $c|upper}x{/capture}',
	'{l}{r}{syntax double}{{$a}}{/syntax}',
	'{dump $a}{debugbreak $a > 1}{trace}{exitIf $b}',
	'{try}x{rollback}{else}y{/try}',
	'{foreach $items as $key => [$a, [$b]]}{first}f{/first}{sep}, {/sep}{last}l{/last}{skipIf $a}{breakIf $b}{continueIf $a}{iterateWhile $a}{$key}{/iterateWhile}{iterateWhile}{$b}{/iterateWhile $b}{else}empty{/foreach}',
	'{foreach $items as $obj->prop => $obj->value}{iterateWhile $obj}{/iterateWhile}{/foreach}',
	'{for $i = 0, $j = 1; $i < 10; $i++, $j++}{$i}{/for}',
	'{while $a}x{/while}{while}y{/while $b}',
	'{if $a}a{elseif $b}b{else}c{/if}{if}x{/if $c}',
	'{ifchanged $a, $b}x{else}y{/ifchanged}{ifchanged}{$a}{/ifchanged}',
	'{switch $a}{case 1, 2}x{case 3}y{default}z{/switch}',

	// n:attributes
	'<div n:attr="a => $a, b: $b" n:class="$a ? b, c" n:tag="$tag">x</div>',
	'<div n:if="$a" n:foreach="$items as $k => $v" n:inner-foreach="$v as $i" n:tag-if="$b">{$i}</div>',
	'<div n:ifcontent>{$a}</div><p n:else>{$b}</p>',
	'<div n:if="$a">x</div><p n:elseif="$b">y</p><p n:else>z</p>',
	'<div n:foreach="$items as $i">{$i}</div><p n:else>empty</p>',
	'<div n:try>x</div><p n:else>y</p>',
	'<div n:ifset="$a" n:ifchanged="$b" n:while="$c" n:spaceless n:syntax="double">x</div>',
	'<div n:capture="$x" title="{$a}" data-x={$b} {if $c}checked{/if}>x</div>',
	'<script>var a = {$a};</script><style>a { color: {$b} }</style><a href={$url}>x</a>',
	'<ul n:for="$i = 0; $i < 3; $i++">{$i}</ul><ul n:foreach="$items as $i"><li n:first n:last n:sep>{$i}</li></ul>',
];

$latte = createLatte();
$latte->addFunction('myFunc', fn() => 'f');

foreach ($templates as $template) {
	$ast = $latte->parse($template);
	checkTree($ast, $template);
	$latte->applyPasses($ast);
	checkTree($ast, $template);
}
