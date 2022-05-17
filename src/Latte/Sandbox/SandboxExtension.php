<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Sandbox;

use Latte;


/**
 * Security protection for the sandbox.
 */
final class SandboxExtension extends Latte\Extension
{
	use Latte\Strict;

	public function getTags(): array
	{
		return [
			'sandbox' => [Nodes\SandboxNode::class, 'create'],
		];
	}
}
