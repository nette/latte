<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{
	public const Blocks = [
		['test' => 'blockTest', 'outer' => 'blockOuter'],
	];


	public function main(array $ʟ_args): void
	{
		extract($ʟ_args);
		unset($ʟ_args);

		echo '
a) ';
		$this->renderBlock('test', [1] + [], 'html') /* line %d%:%d% */;
		echo '


b) ';
		$this->renderBlock('outer', get_defined_vars(), 'html') /* line %d%:%d% */;
		echo '

';
		$var1 = 'outer' /* line %d%:%d% */;
		echo 'c) ';
		$this->renderBlock('test', [], 'html') /* line %d%:%d% */;
		echo '

d) ';
		$this->renderBlock('test', [null] + [], 'html') /* line %d%:%d% */;
	}


	/** {define test $var1, $var2, $var3} on line %d% */
	public function blockTest(array $ʟ_args): void
	{
		extract($this->params);
		$var1 = $ʟ_args[0] ?? $ʟ_args['var1'] ?? null;
		$var2 = $ʟ_args[1] ?? $ʟ_args['var2'] ?? null;
		$var3 = $ʟ_args[2] ?? $ʟ_args['var3'] ?? null;
		unset($ʟ_args);

		echo '	Variables ';
		echo LR\HtmlHelpers::escapeText($var1) /* line %d%:%d% */;
		echo ', ';
		echo LR\HtmlHelpers::escapeText($var2) /* line %d%:%d% */;
		echo ', ';
		echo LR\HtmlHelpers::escapeText($hello) /* line %d%:%d% */;
		echo "\n";
	}


	/** {define outer} on line %d% */
	public function blockOuter(array $ʟ_args): void
	{
		extract($this->params);
		extract($ʟ_args);
		unset($ʟ_args);

		$this->renderBlock('test', ['hello'] + [], 'html') /* line %d%:%d% */;
	}
}
