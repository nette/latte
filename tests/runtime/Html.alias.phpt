<?php declare(strict_types=1);

/**
 * Test: Latte\Runtime\Html is a working alias when loaded first
 */

use Latte\Runtime\Html;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$html = new Html('<b>');
Assert::same('<b>', (string) $html);
Assert::type(Latte\Runtime\HtmlStringable::class, $html);
