<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{

	public function main(array $ʟ_args): void
	{
%A%
		echo '<p>Included file #2 (';
		echo LR\HtmlHelpers::escapeText($localvar) /* line %d%:%d% */;
		echo ', ';
		echo LR\HtmlHelpers::escapeText($hello) /* line %d%:%d% */;
		echo ')</p>

Parent: ';
		echo LR\HtmlHelpers::escapeText(($this->global->fn->info)($this, )) /* line %d%:%d% */;
		echo "\n";
	}
}
