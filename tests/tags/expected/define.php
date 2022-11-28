<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{
	public const Blocks = [
		['test' => 'blockTest', 'true' => 'blockTrue'],
	];


	public function main(array $ʟ_args): void
	{
		extract($ʟ_args);
		unset($ʟ_args);

		echo '

';
		$this->renderBlock('test', ['var' => 20] + get_defined_vars(), 'html') /* line 7 */;
		echo '

';
		$this->renderBlock('true', get_defined_vars(), 'html') /* line 10 */;
	}


	public function prepare(): array
	{
		extract($this->params);

		$var = 10 /* line 1 */;
		return get_defined_vars();
	}


	/** {define test} on line 3 */
	public function blockTest(array $ʟ_args): void
	{
		extract($this->params);
		extract($ʟ_args);
		unset($ʟ_args);

		echo '	This is definition #';
		echo LR\Filters::escapeHtmlText($var) /* line 4 */;
		echo "\n";
	}


	/** {define true} on line 9 */
	public function blockTrue(array $ʟ_args): void
	{
		echo 'true';
	}
}
