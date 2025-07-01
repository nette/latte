<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{
	public const Source = 'main';


	public function main(array $ʟ_args): void
	{
%A%
		$this->createTemplate((is_string($ʟ_tmp = true ? 'inc' : '') ? $ʟ_tmp : throw new InvalidArgumentException(sprintf('Template name must be a string, %s given.', get_debug_type($ʟ_tmp)))), $this->params, 'includeblock')->renderToContentType('html') /* line 2:1 */;
		echo "\n";
		$this->renderBlock('test', [], 'html') /* line 4:1 */;
	}
}
