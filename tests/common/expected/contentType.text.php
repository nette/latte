<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{
	public const ContentType = 'text';


	public function main(array $ʟ_args): void
	{
		extract($ʟ_args);
		unset($ʟ_args);

		echo 'Pure text ';
		echo ($this->filters->escape)($foo) /* pos 1:11 */;
		echo '
<a b
';
	}
}
