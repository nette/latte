<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte;


/**
 * Latte engine feature flags.
 */
enum Feature
{
	/** Adds declare(strict_types=1) to compiled templates (enabled by default) */
	case StrictTypes;

	/** Enforces strict HTML parsing */
	case StrictParsing;

	/** Shows warnings for deprecated Latte 3.0 syntax */
	case MigrationWarnings;

	/** Variables from {foreach} exist only within the loop */
	case ScopedLoopVariables;
}
