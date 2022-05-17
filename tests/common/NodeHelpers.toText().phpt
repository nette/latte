<?php

declare(strict_types=1);

use Latte\Compiler\NodeHelpers;
use Latte\Compiler\Nodes\AuxiliaryNode;
use Latte\Compiler\Nodes\FragmentNode;
use Latte\Compiler\Nodes\Html\QuotedValue;
use Latte\Compiler\Nodes\NopNode;
use Latte\Compiler\Nodes\TextNode;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$fragment = new FragmentNode;

Assert::same('', NodeHelpers::toText($fragment));

$fragment->append(new TextNode('hello'));
Assert::same('hello', NodeHelpers::toText($fragment));

$fragment->append(new TextNode('world'));
Assert::same('helloworld', NodeHelpers::toText($fragment));

$fragment->append(new FragmentNode([new TextNode('!')]));
Assert::same('helloworld!', NodeHelpers::toText($fragment));

$fragment->children[] = new NopNode; // is ignored by append
Assert::same('helloworld!', NodeHelpers::toText($fragment));

$fragment->append(new QuotedValue(new TextNode('quote'), '"'));
Assert::same('helloworld!quote', NodeHelpers::toText($fragment));

$fragment->append(new AuxiliaryNode(fn() => ''));
Assert::null(NodeHelpers::toText($fragment));
