<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{
%A%
	public function main(array $ʟ_args): void
	{
		extract($ʟ_args);
		unset($ʟ_args);

		echo "\n";
		ob_start(fn() => '') /* line %d% */;
		try {
			$this->renderBlock('bar', get_defined_vars()) /* line %d% */;
		} finally {
			$ʟ_tmp = ob_get_length() ? new LR\Html(ob_get_clean()) : ob_get_clean();
		}
		$ʟ_fi = new LR\FilterInfo('html');
		$foo = $ʟ_tmp;

		echo "\n";
		$this->renderBlock('content', get_defined_vars()) /* line %d% */;
	}


	public function prepare(): array
	{
		extract($this->params);

		$this->parentName = 'layout.latte';
		return get_defined_vars();
	}
%A%
}
