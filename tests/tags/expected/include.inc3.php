<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{
	public const Source = '%a%.latte';


	public function main(array $ʟ_args): void
	{
%A%
		echo '<p>Included file #3 (';
		echo LR\HtmlHelpers::escapeText($localvar) /* line %d% */;
		echo ', ';
		echo LR\HtmlHelpers::escapeText($hello) /* line %d% */;
		echo ')</p>
';
	}
}
