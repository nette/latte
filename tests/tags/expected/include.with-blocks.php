<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{

	public function main(array $ʟ_args): void
	{
%A%
		$this->createTemplate(true ? 'inc' : '', $this->params, 'includeblock')->renderToContentType('html') /* line %d% */;
		echo "\n";
		$this->renderBlock('test', [], 'html') /* line %d% */;
	}
}
