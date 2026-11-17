<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Runtime;

if (false) {
	class Html extends TrustedHtml
	{
	}
} elseif (!class_exists(Html::class)) {
	class_alias(TrustedHtml::class, Html::class);
}
