<?php declare(strict_types=1);
%A%
final class Template%a% extends Latte\Runtime\Template
{
	public const ContentType = 'text';


	public function main(array $ʟ_args): void
	{
		extract($ʟ_args);
		unset($ʟ_args);

		echo 'Pure text ';
		echo ((is_object($ʟ_val = $foo) ? LR\Helpers::convertTrusted($ʟ_val, 'text') : null) ?? ($this->filters->escape)($ʟ_val)) /* pos 1:11 */;
		echo '
<a b
';
	}
}
