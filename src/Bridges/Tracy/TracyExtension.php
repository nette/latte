<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Bridges\Tracy;

use Latte\Engine;
use Latte\Extension;
use Latte\Runtime\Template;


/**
 * Extension for Tracy 2.x
 */
class TracyExtension extends Extension
{
	private LattePanel $panel;


	public function __construct(?string $name = null)
	{
		$this->panel = new LattePanel(name: $name);
		BlueScreenPanel::initialize();
	}


	public function beforeCompile(Engine $engine): void
	{
	}


	public function beforeRender(Template $template): void
	{
		$this->panel->addTemplate($template);
	}
}
