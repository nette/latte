<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Bridges\Tracy;

use Latte;
use Tracy;
use Tracy\BlueScreen;
use Tracy\Helpers;
use const ENT_IGNORE;


/**
 * BlueScreen panels for Tracy 2.x
 * @internal
 */
class BlueScreenPanel
{
	private static bool $initialized = false;


	public static function initialize(?BlueScreen $blueScreen = null): void
	{
		if (self::$initialized) {
			return;
		}
		self::$initialized = true;

		$blueScreen ??= Tracy\Debugger::getBlueScreen();
		$blueScreen->addPanel(self::renderError(...));
		$blueScreen->addAction(self::renderUnknownMacro(...));
		if (
			version_compare(Tracy\Debugger::VERSION, '2.9.0', '>=')
			&& version_compare(Tracy\Debugger::VERSION, '3.0', '<')
		) {
			Tracy\Debugger::addSourceMapper(self::mapLatteSourceCode(...));
			$blueScreen->addFileGenerator(fn(string $file) => substr($file, -6) === '.latte'
					? "{block content}\n\$END\$"
					: null);
		}
	}


	public static function renderError(?\Throwable $e): ?array
	{
		if ($e instanceof Latte\CompileException && $e->getSource()) {
			$source = $e->getSource();
			return [
				'tab' => 'Template',
				'panel' =>
					match (true) {
						$source->isFile() => '<p><b>File:</b> ' . Helpers::editorLink($source->name, $source->line) . '</p>',
						(bool) $source->name => '<p><b>' . htmlspecialchars($source->name . ($source->line ? ':' . $source->line : '')) . '</b></p>',
						default => '',
					}
					. '<pre class="code tracy-code"><div>'
					. BlueScreen::highlightLine(htmlspecialchars($source->getCode(), ENT_IGNORE, 'UTF-8'), $source->line ?? 0, 15, $source->column ?? 0)
					. '</div></pre>',
			];
		}

		return null;
	}


	public static function renderUnknownMacro(?\Throwable $e): ?array
	{
		if (
			$e instanceof Latte\CompileException
			&& ($source = $e->getSource())
			&& $source->isFile()
			&& (preg_match('#Unknown tag (\{\w+)\}, did you mean (\{\w+)\}\?#A', $e->getMessage(), $m)
				|| preg_match('#Unknown attribute (n:\w+), did you mean (n:\w+)\?#A', $e->getMessage(), $m))
		) {
			return [
				'link' => Helpers::editorUri($source->name, $source->line, 'fix', $m[1], $m[2]),
				'label' => 'fix it',
			];
		}

		return null;
	}


	/** @return array{file: string, line: int, label: string, active: bool} */
	public static function mapLatteSourceCode(string $file, int $line): ?array
	{
		return ($source = Latte\SourceReference::fromCompiled($file, $line))
			? ['file' => $source->name, 'line' => $source->line ?? 0, 'label' => 'Latte', 'active' => true]
			: null;
	}
}
