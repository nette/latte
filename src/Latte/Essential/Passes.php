<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential;

use Latte;
use Latte\Compiler\Node;
use Latte\Compiler\Nodes\TemplateNode;
use Latte\Compiler\NodeTraverser;
use Latte\Essential\Nodes\ForeachNode;


final class Passes
{
	use Latte\Strict;

	/**
	 * Checks if foreach overrides template variables.
	 */
	public static function overwrittenVariablesPass(TemplateNode $node): void
	{
		$vars = [];
		(new NodeTraverser)->traverse($node, function (Node $node) use (&$vars) {
			if ($node instanceof ForeachNode && $node->checkArgs) {
				preg_match('#.+\s+as\s*\$(\w+)(?:\s*=>\s*\$(\w+))?#i', $node->args->text, $m);
				for ($i = 1; $i < count($m); $i++) {
					$vars[$m[$i]][] = $node->position->line;
				}
			}
		});
		if ($vars) {
			$node->initialization .= str_replace(
				'%dump',
				Latte\Compiler\PhpHelpers::dump(array_map(fn($l) => implode(', ', $l), $vars)),
				<<<'XX'
					if (!$this->getReferringTemplate() || $this->getReferenceType() === 'extends') {
						foreach (array_intersect_key(%dump, $this->params) as $ʟ_v => $ʟ_l) {
							trigger_error("Variable \$$ʟ_v overwritten in foreach on line $ʟ_l");
						}
					}

					XX,
			);
		}
	}
}
