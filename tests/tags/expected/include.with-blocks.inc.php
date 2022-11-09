<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{
	public const Blocks = [
		['test' => 'blockTest'],
	];


	public function main(array $ʟ_args): void
	{
		echo "\n";
	}


	/** {define test} on line %d% */
	public function blockTest(array $ʟ_args): void
	{
		extract($this->params);
		extract($ʟ_args);
		unset($ʟ_args);

		echo '	Parent: ';
		echo LR\Filters::escapeHtmlText(basename($this->getReferringTemplate()->getName())) /* line %d% */;
		echo '/';
		echo LR\Filters::escapeHtmlText($this->getReferenceType()) /* line %d% */;
		echo "\n";
	}
}
