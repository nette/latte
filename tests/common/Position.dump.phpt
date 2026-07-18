<?php

/**
 * Pinned extents of a few minimal templates, each aimed at one trap: the span up to
 * the closing tag, the tree/source inversion of an n:attribute, the offset shift of
 * an unquoted attribute value, and the ranges in an expression subtree.
 */

declare(strict_types=1);

use Latte\Compiler\Node;
use Latte\Compiler\Nodes\TextNode;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


function dumpExtents(string $template): string
{
	$ast = (new Latte\Engine)->parse($template);
	$res = '';
	$walk = function (Node $node, int $depth) use (&$walk, &$res): void {
		$label = str_repeat('    ', $depth) . substr($node::class, strrpos($node::class, '\\') + 1);
		$extent = $node->position ? "{$node->position->offset}..{$node->end->offset}" : '';
		$content = $node instanceof TextNode ? " '" . addcslashes($node->content, "\n\t\r") . "'" : '';
		$res .= rtrim(sprintf('%-40s %s', $label, $extent . $content)) . "\n";
		foreach ($node as $child) {
			$walk($child, $depth + 1);
		}
	};
	$walk($ast, 0);
	return rtrim($res);
}


// the extent of a pair tag spans up to its closing tag
Assert::match(<<<'XX'
	TemplateNode
	    FragmentNode
	    FragmentNode                         0..13
	        IfNode                           0..13
	            VariableNode                 4..6
	            FragmentNode                 7..8
	                TextNode                 7..8 'x'
	XX, dumpExtents('{if $a}x{/if}'));


// an n:attribute inverts the tree/source relation: the wrapper spans the attribute,
// its child element the whole tag
Assert::match(<<<'XX'
	TemplateNode
	    FragmentNode
	    FragmentNode                         5..14
	        IfNode                           5..14
	            VariableNode                 11..13
	            FragmentNode                 0..22
	                ElementNode              0..22
	                    FragmentNode
	                        TextNode          ''
	                    FragmentNode         15..16
	                        TextNode         15..16 'x'
	XX, dumpExtents('<div n:if="$a">x</div>'));


// the unquoted n:attribute value keeps exact offsets
Assert::match(<<<'XX'
	TemplateNode
	    FragmentNode
	    FragmentNode                         0..24
	        ElementNode                      0..24
	            FragmentNode                 5..16
	                NClassNode               5..16
	                    ArrayNode            13..16
	                        ArrayItemNode    13..16
	                            StringNode   13..16
	            FragmentNode                 17..18
	                TextNode                 17..18 'y'
	XX, dumpExtents('<div n:class=foo>y</div>'));


// extents in an expression subtree
Assert::match(<<<'XX'
	TemplateNode
	    FragmentNode
	    FragmentNode                         0..13
	        PrintNode                        0..13
	            VariableNode                 1..3
	            ModifierNode                 3..12
	                FilterNode               3..12
	                    IdentifierNode       4..10
	                    ArgumentNode         11..12
	                        IntegerNode      11..12
	XX, dumpExtents('{$x|filter:1}'));
