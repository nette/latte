<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{

	public function main(array $ʟ_args): void
	{
		extract($ʟ_args);
		unset($ʟ_args);

		echo '
named arguments import

';
		$this->createTemplate('import.latte', $this->params, "import")->render() /* pos %d%:%d% */;
		echo '
a) ';
		$this->renderBlock('test', [1, 'var1' => 2] + [], 'html') /* pos %d%:%d% */;
		echo '

b) ';
		$this->renderBlock('test', ['var2' => 1] + [], 'html') /* pos %d%:%d% */;
		echo '

c) ';
		$this->renderBlock('test', ['hello' => 1] + [], 'html') /* pos %d%:%d% */;
	}
}
