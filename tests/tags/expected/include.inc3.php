<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{

	public function main(array $ʟ_args): void
	{
%A%
		echo '<p>Included file #3 (';
		echo LR\Filters::escapeHtmlText($localvar) /* line %d% */;
		echo ', ';
		echo LR\Filters::escapeHtmlText($hello) /* line %d% */;
		echo ')</p>
';
	}
}
