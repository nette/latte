<?php

/**
 * Test: Latte\Tokenizer::getCoordinates
 */

declare(strict_types=1);

use Latte\Compiler\Position;
use Latte\Compiler\Tokenizer;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::equal(new Position(1, 1), Tokenizer::getCoordinates("say \n123", 0));
Assert::equal(new Position(1, 2), Tokenizer::getCoordinates("say \n123", 1));
Assert::equal(new Position(1, 5), Tokenizer::getCoordinates("say \n123", 4));
Assert::equal(new Position(2, 1), Tokenizer::getCoordinates("say \n123", 5));
