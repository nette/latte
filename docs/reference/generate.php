<?php

declare(strict_types=1);

/**
 * Regenerates the catalog tables in docs/reference/*.md from the source code.
 * The Notes column is hand-maintained and survives regeneration.
 */

namespace Latte\ReferenceDocs;

use Latte;

require_once __DIR__ . '/../../vendor/autoload.php';


/** @return list<array{file: string, marker: string, header: list<string>, rows: array<string, list<string>>}> */
function buildCatalogs(): array
{
	// the default engine registers these two, see Engine::__construct
	$default = [new Latte\Essential\CoreExtension, new Latte\Sandbox\SandboxExtension];
	$optional = [new Latte\Essential\RawPhpExtension, new Latte\Essential\TranslatorExtension(null)];

	$tags = $filters = $functions = $optTags = $optFilters = [];
	foreach ($default as $extension) {
		foreach ($extension->getTags() as $name => $callable) {
			$tags[$name] = [tagDisplay($name), '`' . formatCallable($callable) . '`'];
		}
		foreach ($extension->getFilters() as $name => $callable) {
			$filters[$name] = ['`' . $name . '`', '`' . formatCallable($callable) . '`', isContextual($callable) ? 'yes' : ''];
		}
		foreach ($extension->getFunctions() as $name => $callable) {
			$functions[$name] = ['`' . $name . '()`', '`' . formatCallable($callable) . '`'];
		}
	}

	foreach ($optional as $extension) {
		$extName = '`' . (new \ReflectionObject($extension))->getShortName() . '`';
		foreach ($extension->getTags() as $name => $callable) {
			$optTags[$name] = [tagDisplay($name), $extName, '`' . formatCallable($callable) . '`'];
		}
		foreach ($extension->getFilters() as $name => $callable) {
			$optFilters[$name] = ['`' . $name . '`', $extName, '`' . formatCallable($callable) . '`', isContextual($callable) ? 'yes' : ''];
		}
	}

	foreach ([&$tags, &$filters, &$functions, &$optTags, &$optFilters] as &$rows) {
		uksort($rows, strcasecmp(...));
	}

	return [
		['file' => __DIR__ . '/tags.md', 'marker' => 'tags', 'header' => ['Tag', 'Implementation', 'Notes'], 'rows' => $tags],
		['file' => __DIR__ . '/tags.md', 'marker' => 'tags-optional', 'header' => ['Tag', 'Extension', 'Implementation', 'Notes'], 'rows' => $optTags],
		['file' => __DIR__ . '/filters.md', 'marker' => 'filters', 'header' => ['Filter', 'Implementation', 'Contextual', 'Notes'], 'rows' => $filters],
		['file' => __DIR__ . '/filters.md', 'marker' => 'filters-optional', 'header' => ['Filter', 'Extension', 'Implementation', 'Contextual', 'Notes'], 'rows' => $optFilters],
		['file' => __DIR__ . '/filters.md', 'marker' => 'functions', 'header' => ['Function', 'Implementation', 'Notes'], 'rows' => $functions],
	];
}


function tagDisplay(string $name): string
{
	return '`' . (str_starts_with($name, 'n:') ? $name : '{' . $name . '}') . '`';
}


function formatCallable(callable $callable): string
{
	if (is_string($callable)) {
		return $callable . '()';
	}
	$ref = new \ReflectionFunction(\Closure::fromCallable($callable));
	$class = $ref->getClosureScopeClass()?->getShortName();
	return match (true) {
		str_contains($ref->getName(), '{closure') => 'closure in ' . ($class ?? '?'),
		$class === null => $ref->getName() . '()',
		default => $class . '::' . $ref->getName(),
	};
}


function isContextual(callable $callable): bool
{
	$ref = new \ReflectionFunction(\Closure::fromCallable($callable));
	$type = ($ref->getParameters()[0] ?? null)?->getType();
	return $type instanceof \ReflectionNamedType
		&& $type->getName() === Latte\Runtime\FilterInfo::class;
}


/**
 * Extracts the hand-maintained Notes column (the last cell) from an existing table.
 * @return array<string, string>
 */
function parseNotes(string $block): array
{
	$notes = [];
	foreach (explode("\n", $block) as $line) {
		$line = trim($line);
		if (!str_starts_with($line, '|')) {
			continue;
		}
		$cells = array_map(trim(...), preg_split('~(?<!\\\)\|~', trim($line, '|')));
		$first = $cells[0] ?? '';
		$note = end($cells);
		if ($note === '' || !str_starts_with($first, '`')) {
			continue;
		}
		$key = preg_replace('~\(\)$~', '', trim(trim($first, '`'), '{}'));
		$notes[$key] = $note;
	}
	return $notes;
}


/** @param array<string, list<string>> $rows */
function buildTable(array $header, array $rows, array $notes): string
{
	$lines = ['| ' . implode(' | ', $header) . ' |'];
	$lines[] = str_repeat('|---', count($header)) . '|';
	foreach ($rows as $key => $cells) {
		$cells[] = $notes[$key] ?? '';
		$lines[] = '| ' . implode(' | ', $cells) . ' |';
	}
	return implode("\n", $lines) . "\n";
}


/** @return array<string, string>  file => regenerated content */
function regenerate(): array
{
	$contents = [];
	foreach (buildCatalogs() as $catalog) {
		$file = $catalog['file'];
		$contents[$file] ??= str_replace("\r\n", "\n", file_get_contents($file));
		$open = "<!-- generated: $catalog[marker] -->";
		$pattern = '~' . preg_quote($open, '~') . '\n(.*?)<!-- /generated -->~s';
		if (!preg_match($pattern, $contents[$file], $m)) {
			throw new \RuntimeException("Missing '$open' block in $file");
		}
		$table = buildTable($catalog['header'], $catalog['rows'], parseNotes($m[1]));
		$contents[$file] = preg_replace_callback(
			$pattern,
			fn() => $open . "\n" . $table . '<!-- /generated -->',
			$contents[$file],
		);
	}
	return $contents;
}


if (!debug_backtrace()) {
	foreach (regenerate() as $file => $content) {
		if ($content === str_replace("\r\n", "\n", file_get_contents($file))) {
			echo basename($file), ": up to date\n";
		} else {
			file_put_contents($file, $content);
			echo basename($file), ": updated\n";
		}
	}
}
