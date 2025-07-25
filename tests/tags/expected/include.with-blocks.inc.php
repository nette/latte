<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{
	public const Source = 'inc';

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
		echo LR\HtmlHelpers::escapeText(basename($this->getReferringTemplate()->getName())) /* line %d% */;
		echo '/';
		echo LR\HtmlHelpers::escapeText($this->getReferenceType()) /* line %d% */;
		echo "\n";
	}
}
