<?php

declare(strict_types=1);

use Latte\Compiler\NodeHelpers;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/nodehelpers.php';

$leafNode1 = new LeafNode;
$leafNode2 = new LeafNode;
$parentNode = new ParentNode($leafNode2);
$node = new ArrayNode([$leafNode1, $parentNode]);

$newNode = NodeHelpers::clone($node);

Assert::equal($node, $newNode);
Assert::notSame($node, $newNode);

Assert::equal($node->items[0], $newNode->items[0]);
Assert::notSame($node->items[0], $newNode->items[0]);

Assert::equal($node->items[1], $newNode->items[1]);
Assert::notSame($node->items[1], $newNode->items[1]);

Assert::equal($node->items[1]->child, $newNode->items[1]->child);
Assert::notSame($node->items[1]->child, $newNode->items[1]->child);
