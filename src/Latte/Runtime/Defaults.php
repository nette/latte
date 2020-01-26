<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Runtime;


/**
 * @internal
 */
class Defaults
{
	/** @var array<string,callable> */
	private $list = [
		'batch' => [Filters::class, 'batch'],
		'breakLines' => [Filters::class, 'breaklines'],
		'bytes' => [Filters::class, 'bytes'],
		'capitalize' => [Filters::class, 'capitalize'],
		'dataStream' => [Filters::class, 'dataStream'],
		'date' => [Filters::class, 'date'],
		'escapeCss' => [Filters::class, 'escapeCss'],
		'escapeHtml' => [Filters::class, 'escapeHtml'],
		'escapeHtmlComment' => [Filters::class, 'escapeHtmlComment'],
		'escapeICal' => [Filters::class, 'escapeICal'],
		'escapeJs' => [Filters::class, 'escapeJs'],
		'escapeUrl' => 'rawurlencode',
		'escapeXml' => [Filters::class, 'escapeXml'],
		'firstUpper' => [Filters::class, 'firstUpper'],
		'checkUrl' => [Filters::class, 'safeUrl'],
		'implode' => [Filters::class, 'implode'],
		'indent' => [Filters::class, 'indent'],
		'length' => [Filters::class, 'length'],
		'lower' => [Filters::class, 'lower'],
		'number' => 'number_format',
		'padLeft' => [Filters::class, 'padLeft'],
		'padRight' => [Filters::class, 'padRight'],
		'repeat' => [Filters::class, 'repeat'],
		'replace' => [Filters::class, 'replace'],
		'replaceRe' => [Filters::class, 'replaceRe'],
		'reverse' => [Filters::class, 'reverse'],
		'strip' => [Filters::class, 'strip'],
		'stripHtml' => [Filters::class, 'stripHtml'],
		'stripTags' => [Filters::class, 'stripTags'],
		'substr' => [Filters::class, 'substring'],
		'trim' => [Filters::class, 'trim'],
		'truncate' => [Filters::class, 'truncate'],
		'upper' => [Filters::class, 'upper'],
		'webalize' => [\Nette\Utils\Strings::class, 'webalize'],
	];


	/** @return array<string,callable> */
	public function getFilters(): array
	{
		return $this->list;
	}
}
