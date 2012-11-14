<?php

/**
 * Nette Framework
 *
 * @copyright  Copyright (c) 2004, 2010 David Grudl
 * @license    http://nette.org/license  Nette license
 * @link       http://nette.org
 * @category   Nette
 * @package    Nette\Templates
 */

namespace Nette\Templates;

use Nette,
	Nette\Environment,
	Nette\Caching\Cache;



/**
 * Caching template helper.
 *
 * @copyright  Copyright (c) 2004, 2010 David Grudl
 * @package    Nette\Templates
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
	 * @param  CachingHelper
	 * @param  array
	 * @return CachingHelper
	 */
	public static function create($key, & $parents, $args = NULL)
	{
		if ($args) {
			$key .= md5(serialize($args));
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
				Cache::EXPIRE => isset($args['expire']) ? $args['expire'] : '+ 7 days',
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
		$this->getCache()->save($this->key, ob_get_flush(), $this->frame);
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