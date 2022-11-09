<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{
	public const Blocks = [
		['test' => 'blockTest'],
	];


	public function main(array $ʟ_args): void
	{
		extract($ʟ_args);
		unset($ʟ_args);

		echo "\n";
		$this->renderBlock('test', [1] + [], 'html') /* line %d% */;
	}


	/** {define test $var1, ?stdClass $var2, \C\B|null $var3} on line %d% */
	public function blockTest(array $ʟ_args): void
	{
	}
}
