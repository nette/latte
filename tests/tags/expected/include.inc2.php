<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{

	public function main(array $ʟ_args): void
	{
%A%
		echo '<p>Included file #2 (';
		echo LR\Filters::escapeHtmlText($localvar) /* line %d%:%d% */;
		echo ', ';
		echo LR\Filters::escapeHtmlText($hello) /* line %d%:%d% */;
		echo ')</p>

Parent: ';
		echo LR\Filters::escapeHtmlText(basename($this->getReferringTemplate()->getName())) /* line %d%:%d% */;
		echo '/';
		echo LR\Filters::escapeHtmlText($this->getReferenceType()) /* line %d%:%d% */;
		echo "\n";
	}
}
