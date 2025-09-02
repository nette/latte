<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{

	public function main(array $ʟ_args): void
	{
%A%
		echo '<p>Included file #3 (';
		echo LR\HtmlHelpers::escapeText($localvar) /* line %d%:%d% */;
		echo ', ';
		echo LR\HtmlHelpers::escapeText($hello) /* line %d%:%d% */;
		echo ')</p>
';
	}
}
