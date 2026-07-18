<?php

/**
 * Invariants of node source extents: every node the parser produces carries a truthful
 * $position/$end pair. No expected files, nothing to regenerate.
 */

declare(strict_types=1);

use Latte\Compiler\Node;
use Latte\Compiler\Nodes\FragmentNode;
use Latte\Compiler\Nodes\TextNode;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$corpus = [
	'plain text',
	"multi\nline\ntext",
	"crlf\r\nline",
	'{$var}',
	'{$x|truncate:10|upper}',
	'{="a" . "b"}',
	'{if $a}one{$b}two{/if}',
	'{if $a}A{elseif $b}B{else}C{/if}',
	"{block foo}\n\t<p>hello</p>\n\t<p>world</p>\n{/block}",
	'{foreach $items as $item}x{else}empty{/foreach}',
	'{switch $a}{case 1}one{case 2}two{default}other{/switch}',
	'{* comment *}text{* another *}',
	"{* c *}\ntext",
	"{var \$x = 1}\n{\$x}",
	'{capture $x}abc{/capture}',
	'{syntax double}{{$a}}{/syntax}',
	'<div>a{$b}c</div>',
	'<a href="url" title=plain>x</a>',
	'<p title="{$a} b">x</p>',
	'<div n:if="$a">x</div>',
	'<div n:class=foo>y</div>',
	'<div n:inner-if="$a">x</div>',
	'<div n:tag-if="$a">x</div>',
	"<ul>\n\t<li n:foreach=\"\$items as \$item\">{\$item}</li>\n</ul>",
	'<div {if $a}class=x{/if}>y</div>',
	"<div>\n\t{if \$a}\n\t\tx\n\t{/if}\n</div>",
	'<!-- comment {$a} -->',
	'<script>var a = {$x};</script>',
	'<br>',
];

$problems = [];

foreach ($corpus as $i => $template) {
	$source = str_replace("\r\n", "\n", $template); // mirrors lexer's normalization
	$ast = (new Latte\Engine)->parse($template);
	$report = function (Node $node, string $message) use (&$problems, $i, $template) {
		$name = substr($node::class, strrpos($node::class, '\\') + 1);
		$problems[] = "#$i " . substr($template, 0, 30) . " | $name: $message";
	};

	$walk = function (Node $node, ?Node $parent) use (&$walk, $source, $report): void {
		[$pos, $end] = [$node->position, $node->end];

		// the pair is complete, ordered and within the source
		if (($pos === null) !== ($end === null)) {
			$report($node, 'has only one of position/end');
		}
		foreach ([$pos, $end] as $point) {
			if ($point) {
				if ($point->offset < 0 || $point->offset > strlen($source)) {
					$report($node, "offset $point->offset out of bounds");
				}
				// line & column match the values derived from the offset
				$prefix = substr($source, 0, $point->offset);
				$line = substr_count($prefix, "\n") + 1;
				$column = ($nl = strrpos($prefix, "\n")) === false ? $point->offset + 1 : $point->offset - $nl;
				if ($point->line !== $line || $point->column !== $column) {
					$report($node, "offset $point->offset gives $line:$column but claims $point->line:$point->column");
				}
			}
		}
		if ($pos && $end && $end->offset < $pos->offset) {
			$report($node, "ends at $end->offset before it starts at $pos->offset");
		}

		// a fragment spans exactly its children
		if ($node instanceof FragmentNode) {
			$min = $max = null;
			foreach ($node->children as $child) {
				if ($child->position && (!$min || $child->position->offset < $min->offset)) {
					$min = $child->position;
				}
				if ($child->end && (!$max || $child->end->offset > $max->offset)) {
					$max = $child->end;
				}
			}
			if ($pos?->offset !== $min?->offset || $end?->offset !== $max?->offset) {
				$report($node, 'extent does not match the min/max of its children');
			}
		}

		// text is a slice of the source (zero-width anchors after clear() included)
		if ($node instanceof TextNode && $pos && $end
			&& substr($source, $pos->offset, $end->offset - $pos->offset) !== $node->content
		) {
			$report($node, "content does not match the source slice at $pos->offset..$end->offset");
		}

		// extents never overlap partially: a child lies inside its parent, contains it
		// entirely, or is disjoint (n:attributes invert or detach the tree/source
		// relation by design)
		if ($parent?->position && $parent->end && $pos && $end) {
			$overlap = max($pos->offset, $parent->position->offset) < min($end->offset, $parent->end->offset);
			$inside = $pos->offset >= $parent->position->offset && $end->offset <= $parent->end->offset;
			$contains = $pos->offset <= $parent->position->offset && $end->offset >= $parent->end->offset;
			if ($overlap && !$inside && !$contains) {
				$report($node, "extent $pos->offset..$end->offset crosses parent {$parent->position->offset}..{$parent->end->offset}");
			}
		}

		foreach ($node as $child) {
			$walk($child, $node);
		}
	};
	$walk($ast, null);
}

Assert::same([], $problems);
