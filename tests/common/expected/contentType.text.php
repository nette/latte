<?php

use Latte\Runtime as LR;

/** source: W:\Nette\Latte\tests\common/templates/contentType.text.latte */
final class Template0ddeb076b5 extends Latte\Runtime\Template
{
	public const ContentType = 'text';


	public function main(array $ʟ_args): void
	{
		extract($ʟ_args);
		unset($ʟ_args);

		echo 'Pure text ';
		echo ($this->filters->escape)($foo) /* line 1 */;
		echo '
<a b
';
	}
}
