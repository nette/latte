<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{

	public function main(array $ʟ_args): void
	{
%A%
		$this->renderBlock('test', [], 'html') /* line 3 */;
	}


	public function prepare(): array
	{
		extract($this->params);

		$this->createTemplate('inc', $this->params, "import")->render() /* line 2 */;
		return get_defined_vars();
	}
}
