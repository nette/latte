<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{
	public const Blocks = [
		['content' => 'blockContent'],
	];


	public function main(array $ʟ_args): void
	{
%A%
		$this->renderBlock('content', get_defined_vars()) /* line %d% */;
	}


	public function prepare(): array
	{
		extract($this->params);

		$this->parentName = true ? $ext : 'undefined';
		return get_defined_vars();
	}


	/** {block content} on line %d% */
	public function blockContent(array $ʟ_args): void
	{
		echo '	Content
';
	}
}
