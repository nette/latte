<?php

/**
 * Test: {templateType}
 * @phpVersion 8
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

class ExampleTemplateType {
  public $a;
  public int $b;
  public ExampleTemplateType|int|null $c;
  private $private;
}

Assert::matchFile(
  __DIR__ . '/expected/CoreMacros.templateType.80.phtml',
  $latte->compile('{templateType ExampleTemplateType}{define test}{$a}{/define}')
);
