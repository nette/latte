<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{

	public function main(array $ʟ_args): void
	{
%A%
		$this->renderBlock('test', [], 'html') /* pos 3:3 */;
	}


	public function prepare(): array
	{
		extract($this->params);

		$this->createTemplate('inc', $this->params, "import")->render() /* pos 2:3 */;
		return get_defined_vars();
	}
}
