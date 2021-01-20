<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Runtime;

use Latte\RuntimeException;


/**
 * @internal
 */
class Defaults
{
	/** @return array<string, callable> */
	public function getFilters(): array
	{
		return [
			'batch' => [Filters::class, 'batch'],
			'breakLines' => [Filters::class, 'breaklines'],
			'bytes' => [Filters::class, 'bytes'],
			'capitalize' => extension_loaded('mbstring')
				? [Filters::class, 'capitalize']
				: function () { throw new RuntimeException('Filter |capitalize requires mbstring extension.'); },
			'clamp' => [Filters::class, 'clamp'],
			'dataStream' => [Filters::class, 'dataStream'],
			'date' => [Filters::class, 'date'],
			'escapeCss' => [Filters::class, 'escapeCss'],
			'escapeHtml' => [Filters::class, 'escapeHtml'],
			'escapeHtmlComment' => [Filters::class, 'escapeHtmlComment'],
			'escapeICal' => [Filters::class, 'escapeICal'],
			'escapeJs' => [Filters::class, 'escapeJs'],
			'escapeUrl' => 'rawurlencode',
			'escapeXml' => [Filters::class, 'escapeXml'],
			'firstUpper' => extension_loaded('mbstring')
				? [Filters::class, 'firstUpper']
				: function () { throw new RuntimeException('Filter |firstUpper requires mbstring extension.'); },
			'checkUrl' => [Filters::class, 'safeUrl'],
			'implode' => [Filters::class, 'implode'],
			'indent' => [Filters::class, 'indent'],
			'length' => [Filters::class, 'length'],
			'lower' => extension_loaded('mbstring')
				? [Filters::class, 'lower']
				: function () { throw new RuntimeException('Filter |lower requires mbstring extension.'); },
			'number' => 'number_format',
			'padLeft' => [Filters::class, 'padLeft'],
			'padRight' => [Filters::class, 'padRight'],
			'query' => function (array $data): string {
				return http_build_query($data, '', '&');
			},
			'repeat' => [Filters::class, 'repeat'],
			'replace' => [Filters::class, 'replace'],
			'replaceRe' => [Filters::class, 'replaceRe'],
			'reverse' => [Filters::class, 'reverse'],
			'sort' => [Filters::class, 'sort'],
			'strip' => [Filters::class, 'strip'],
			'stripHtml' => [Filters::class, 'stripHtml'],
			'stripTags' => [Filters::class, 'stripTags'],
			'substr' => [Filters::class, 'substring'],
			'trim' => [Filters::class, 'trim'],
			'truncate' => [Filters::class, 'truncate'],
			'upper' => extension_loaded('mbstring')
				? [Filters::class, 'upper']
				: function () { throw new RuntimeException('Filter |upper requires mbstring extension.'); },
			'webalize' => class_exists(\Nette\Utils\Strings::class)
				? [\Nette\Utils\Strings::class, 'webalize']
				: function () { throw new RuntimeException('Filter |webalize requires nette/utils package.'); },
		];
	}


	/** @return array<string, callable> */
	public function getFunctions(): array
	{
		return [
			'clamp' => [Filters::class, 'clamp'],
		];
	}
}
