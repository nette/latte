<?php

declare(strict_types=1);


class PolicyLogger implements Latte\Policy
{
	public $log = [];


	public function isMacroAllowed(string $macro): bool
	{
		$this->log['macros'][] = $macro;
		return true;
	}


	public function isFilterAllowed(string $filter): bool
	{
		$this->log['filters'][] = $filter;
		return true;
	}


	public function isFunctionAllowed(string $function): bool
	{
		$this->log['functions'][] = $function;
		return true;
	}


	public function isMethodAllowed(string $class, string $method): bool
	{
		$this->log['methods'][] = [$class, $method];
		return true;
	}


	public function isPropertyAllowed(string $class, string $property): bool
	{
		$this->log['properties'][] = [$class, $property];
		return true;
	}
}
