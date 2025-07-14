<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{
	public const Source = '%a%.latte';


	public function main(array $ʟ_args): void
	{
%A%
		echo '<p>Included file #2 (';
		echo LR\HtmlHelpers::escapeText($localvar) /* line %d% */;
		echo ', ';
		echo LR\HtmlHelpers::escapeText($hello) /* line %d% */;
		echo ')</p>

Parent: ';
		echo LR\HtmlHelpers::escapeText(basename($this->getReferringTemplate()->getName())) /* line %d% */;
		echo '/';
		echo LR\HtmlHelpers::escapeText($this->getReferenceType()) /* line %d% */;
		echo "\n";
	}
}
