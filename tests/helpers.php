<?php

declare(strict_types=1);

use Latte\Compiler\Node;
use Latte\Compiler\Position;
use Tracy\Dumper;


function getTempDir(): string
{
	$dir = __DIR__ . '/tmp/' . getmypid();

	if (empty($GLOBALS['\\lock'])) {
		// garbage collector
		$GLOBALS['\\lock'] = $lock = fopen(__DIR__ . '/lock', 'w');
		if (rand(0, 100)) {
			flock($lock, LOCK_SH);
			@mkdir(dirname($dir));
		} elseif (flock($lock, LOCK_EX)) {
			Tester\Helpers::purge(dirname($dir));
		}

		@mkdir($dir);
	}

	return $dir;
}


function test(string $title, Closure $function): void
{
	$function();
}


function exportNode(Node $node): string
{
	$exporters = [
		Position::class => function (Position $pos, Tracy\Dumper\Value $value) {
			$value->value = $pos->line . ':' . $pos->column . ' (offset ' . $pos->offset . ')';
		},
	];
	$dump = Dumper::toText($node, [Dumper::HASH => false, Dumper::DEPTH => 20, Dumper::OBJECT_EXPORTERS => $exporters]);
	return trim($dump) . "\n";
}
