<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{
	public const ContentType = 'text';

	public const Source = '%a%.latte';


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
