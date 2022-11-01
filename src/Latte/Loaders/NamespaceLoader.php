<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Loaders;

use Latte;


/**
 * Template loader.
 */
class NamespaceLoader implements Latte\Loader
{
    /**
     * @var Latte\Loader[]
     */
    private array $loaders;

    public function __construct(array $loaders)
    {
        $this->loaders = $loaders;
    }

    /**
     * Returns template source code.
     */
    public function getContent(string $name): string
    {
        [$loader, $name] = $this->extractLoaderAndName($name);

        return $loader->getContent($name);
    }


    public function isExpired(string $file, int $time): bool
    {
        [$loader, $name] = $this->extractLoaderAndName($file);

        return $loader->isExpired($name, $time);
    }


    /**
     * Returns referred template name.
     */
    public function getReferredName(string $name, string $referringName): string
    {
        [$loader, $name] = $this->extractLoaderAndName($name);

        return $loader->getReferredName($name, $referringName);
    }


    /**
     * Returns unique identifier for caching.
     */
    public function getUniqueId(string $name): string
    {
        [$loader, $name] = $this->extractLoaderAndName($name);

        return $loader->getUniqueId($name);
    }


    private function extractLoaderAndName(string $name): array
    {
        $namespaceParts = \explode('::', $name, 2);

        if (count($namespaceParts) === 2) {
            return [
                $this->loaders[$namespaceParts[0]],
                $namespaceParts[1],
            ];
        }

        return [
            $this->loaders[''],
            $name,
        ];
    }
}
