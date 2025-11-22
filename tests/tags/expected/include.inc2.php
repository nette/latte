<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{

	public function main(array $ʟ_args): void
	{
%A%
		echo '<p>Included file #2 (';
		echo LR\Filters::escapeHtmlText($localvar) /* pos %d%:%d% */;
		echo ', ';
		echo LR\Filters::escapeHtmlText($hello) /* pos %d%:%d% */;
		echo ')</p>

Parent: ';
		echo LR\Filters::escapeHtmlText(($this->global->fn->info)($this, )) /* pos %d%:%d% */;
		echo "\n";
	}
}
