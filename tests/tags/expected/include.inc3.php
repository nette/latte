<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{
	public const Source = '%a%.latte';


	public function main(array $ʟ_args): void
	{
%A%
		echo '<p>Included file #3 (';
		echo LR\Filters::escapeHtmlText($localvar) /* line %d%:%d% */;
		echo ', ';
		echo LR\Filters::escapeHtmlText($hello) /* line %d%:%d% */;
		echo ')</p>
';
	}
}
