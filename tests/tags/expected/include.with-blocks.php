<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{

	public function main(array $ʟ_args): void
	{
%A%
		$this->createTemplate((LR\Helpers::stringOrNull($ʟ_tmp = true ? 'inc' : '') ?? throw new InvalidArgumentException(sprintf('Template name must be a string, %s given.', get_debug_type($ʟ_tmp)))), $this->params, 'includeblock')->renderToContentType('html') /* line 2 */;
		echo "\n";
		$this->renderBlock('test', [], 'html') /* line %d% */;
	}
}
