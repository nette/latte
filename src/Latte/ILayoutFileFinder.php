<?php
namespace Latte;

/**
 * Layout template file finder
 *
 * @author David Matejka
 */
interface ILayoutFileFinder
{

	/**
	 * @return string|null
	 */
	public function find();
}