<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte;


/**
 * Latte engine feature flags.
 */
final class Feature
{
	/** Adds declare(strict_types=1) to compiled templates */
	public const StrictTypes = 'strictTypes';

	/** Enforces strict HTML parsing */
	public const StrictParsing = 'strictParsing';
}
