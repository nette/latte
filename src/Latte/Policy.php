<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte;

interface Policy
{
    public function isTagAllowed(string $tag): bool;

    public function isFilterAllowed(string $filter): bool;

    public function isFunctionAllowed(string $function): bool;

    public function isMethodAllowed(string $class, string $method): bool;

    public function isPropertyAllowed(string $class, string $property): bool;
}
