<?php declare(strict_types=1);
%A%
final class Template%a% extends Latte\Runtime\Template
{

	public function main(array $ʟ_args): void
	{
		echo 'This should be erased
';
	}


	public function prepare(): array
	{
		extract($this->params);

		$this->parentName = 'parent';
		$foo = 1 /* pos 3:1 */;
		return get_defined_vars();
	}
}
