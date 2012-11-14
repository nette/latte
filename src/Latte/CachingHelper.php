<?php

/**
 * This file is part of the Nette Framework.
 *
 * Copyright (c) 2004, 2011 David Grudl (http://davidgrudl.com)
 *
 * This source file is subject to the "Nette license", and/or
 * GPL license. For more information please see http://nette.org
 */

namespace Nette\Templates;

use Nette,
	Nette\Environment,
	Nette\Caching\Cache;



/**
 * Caching template helper.
 *
 * @author     David Grudl
 */
class CachingHelper extends Nette\Object
{
	/** @var array */
	private $frame;

	/** @var string */
	private $key;



	/**
	 * Starts the output cache. Returns CachingHelper object if buffering was started.
	 * @param  string
	 * @param  array of CachingHelper
	 * @param  array
	 * @return CachingHelper
	 */
	public static function create($key, & $parents, $args = NULL)
	{
		if ($args) {
			if (array_key_exists('if', $args) && !$args['if']) {
				return $parents[] = new self;
			}
			$key = array_merge(array($key), array_intersect_key($args, range(0, count($args))));
		}
		if ($parents) {
			end($parents)->frame[Cache::ITEMS][] = $key;
		}

		$cache = self::getCache();
		if (isset($cache[$key])) {
			echo $cache[$key];
			return FALSE;

		} else {
			$obj = new self;
			$obj->key = $key;
			$obj->frame = array(
				Cache::TAGS => isset($args['tags']) ? $args['tags'] : NULL,
				Cache::EXPIRATION => isset($args['expire']) ? $args['expire'] : '+ 7 days',
			);
			ob_start();
			return $parents[] = $obj;
		}
	}



	/**
	 * Stops and saves the cache.
	 * @return void
	 */
	public function save()
	{
		if ($this->key !== NULL) {
			$this->getCache()->save($this->key, ob_get_flush(), $this->frame);
		}
		$this->key = $this->frame = NULL;
	}



	/**
	 * Adds the file dependency.
	 * @param  string
	 * @return void
	 */
	public function addFile($file)
	{
		$this->frame[Cache::FILES][] = $file;
	}



	/********************* backend ****************d*g**/



	/**
	 * @return Nette\Caching\Cache
	 */
	protected static function getCache()
	{
		return Environment::getCache('Nette.Template.Cache');
	}

}