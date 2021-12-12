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


/**
 * BlueScreen panels for Tracy 2.x
 */
class BlueScreenPanel
{
	public static function initialize(?BlueScreen $blueScreen = null): void
	{
		$blueScreen = $blueScreen ?? Tracy\Debugger::getBlueScreen();
		$blueScreen->addPanel([self::class, 'renderError']);
		$blueScreen->addAction([self::class, 'renderUnknownMacro']);
	}


	public static function renderError(?\Throwable $e): ?array
	{
		if ($e instanceof Latte\CompileException && $e->sourceName) {
			return [
				'tab' => 'Template',
				'panel' => (preg_match('#\n|\?#', $e->sourceName)
						? ''
						: '<p>'
							. (@is_file($e->sourceName) // @ - may trigger error
								? '<b>File:</b> ' . Helpers::editorLink($e->sourceName, $e->sourceLine)
								: '<b>' . htmlspecialchars($e->sourceName . ($e->sourceLine ? ':' . $e->sourceLine : '')) . '</b>')
							. '</p>')
					. '<pre class=code><div>'
					. BlueScreen::highlightLine(htmlspecialchars($e->sourceCode, ENT_IGNORE, 'UTF-8'), $e->sourceLine)
					. '</div></pre>',
			];

		} elseif ($e && strpos($file = $e->getFile(), '.latte--')) {
			$lines = file($file);
			if (
				preg_match('#/\*\* source: (\S+\.latte)#', $lines[4], $m)
				&& @is_file($m[1]) // @ - may trigger error
			) {
				$templateFile = $m[1];
				$templateLine = $e->getLine() && preg_match('#/\* line (\d+) \*/#', $lines[$e->getLine() - 1], $m) ? (int) $m[1] : 0;
				return [
					'tab' => 'Template',
					'panel' => '<p><b>File:</b> ' . Helpers::editorLink($templateFile, $templateLine) . '</p>'
						. ($templateLine === null
							? ''
							: BlueScreen::highlightFile($templateFile, $templateLine)),
				];
			}
		}

		return null;
	}


	public static function renderUnknownMacro(?\Throwable $e): ?array
	{
		if (
			$e instanceof Latte\CompileException
			&& $e->sourceName
			&& @is_file($e->sourceName) // @ - may trigger error
			&& (preg_match('#Unknown tag (\{\w+)\}, did you mean (\{\w+)\}\?#A', $e->getMessage(), $m)
				|| preg_match('#Unknown attribute (n:\w+), did you mean (n:\w+)\?#A', $e->getMessage(), $m))
		) {
			return [
				'link' => Helpers::editorUri($e->sourceName, $e->sourceLine, 'fix', $m[1], $m[2]),
				'label' => 'fix it',
			];
		}

		return null;
	}
}
