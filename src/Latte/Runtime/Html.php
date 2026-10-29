<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Runtime;

use Latte\ContentType;


/**
 * Wraps a pre-escaped HTML string so it is not escaped again when rendered.
 * @extends Content<ContentType::Html>
 */
class Html extends Content implements HtmlStringable
{
	public function __construct(string|\Stringable|null $value)
	{
		parent::__construct((string) $value, ContentType::Html);
	}
}
