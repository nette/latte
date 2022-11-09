<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{
	public const Blocks = [
		['test' => 'blockTest'],
	];


	public function main(array $ʟ_args): void
	{
		extract($ʟ_args);
		unset($ʟ_args);

		echo 'named arguments


a) ';
		$this->renderBlock('test', [1, 'var1' => 2] + [], 'html') /* line %d% */;
		echo '

b) ';
		$this->renderBlock('test', ['var2' => 1] + [], 'html') /* line %d% */;
		echo '

c) ';
		$this->renderBlock('test', ['hello' => 1] + [], 'html') /* line %d% */;
		echo '

d) ';
		$this->renderBlock('test', ['var2' => 1, 2] + [], 'html') /* line %d% */;
		echo ' // invalid';
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
		echo LR\Filters::escapeHtmlText($var1) /* line %d% */;
		echo ', ';
		echo LR\Filters::escapeHtmlText($var2) /* line %d% */;
		echo ', ';
		echo LR\Filters::escapeHtmlText($var3) /* line %d% */;
		echo "\n";
	}
}
