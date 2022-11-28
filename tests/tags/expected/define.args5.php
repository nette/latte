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

		echo 'default values


a) ';
		$this->renderBlock('test', [1] + [], 'html') /* line %d% */;
		echo '

b) ';
		$this->renderBlock('test', ['var1' => 1] + [], 'html') /* line %d% */;
	}


	/** {define test $var1 = 0, $var2 = [1, 2, 3], $var3 = 10} on line %d% */
	public function blockTest(array $ʟ_args): void
	{
		extract($this->params);
		$var1 = $ʟ_args[0] ?? $ʟ_args['var1'] ?? 0;
		$var2 = $ʟ_args[1] ?? $ʟ_args['var2'] ?? [1, 2, 3];
		$var3 = $ʟ_args[2] ?? $ʟ_args['var3'] ?? 10;
		unset($ʟ_args);

		echo '	Variables ';
		echo LR\Filters::escapeHtmlText($var1) /* line %d% */;
		echo ', ';
		echo LR\Filters::escapeHtmlText(($this->filters->implode)($var2)) /* line %d% */;
		echo ', ';
		echo LR\Filters::escapeHtmlText($var3) /* line %d% */;
		echo "\n";
	}
}
