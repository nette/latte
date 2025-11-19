<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{

	public function main(array $ʟ_args): void
	{
%A%
		echo '<p>Included file #3 (';
		echo LR\HtmlHelpers::escapeText($localvar) /* pos %d%:%d% */;
		echo ', ';
		echo LR\HtmlHelpers::escapeText($hello) /* pos %d%:%d% */;
		echo ')</p>
';
	}
}
