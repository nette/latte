<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{
	public const Blocks = [
		1 => ['a' => 'blockA'],
		2 => ['a' => 'blockA1'],
	];


	public function main(array $ʟ_args): void
	{
		extract($ʟ_args);
		unset($ʟ_args);

		echo "\n";
		$this->enterBlockLayer(1, get_defined_vars()) /* pos 2:3 */;
		$this->createTemplate('import.latte', $this->params, "import")->render() /* pos 8:4 */;
		try {
			$this->createTemplate('embed1.latte', [], "embed")->renderToContentType('html') /* pos 2:3 */;
		} finally {
			$this->leaveBlockLayer();
		}
	}


	/** {block a} on line 3 */
	public function blockA(array $ʟ_args): void
	{
		extract(end($this->varStack));
		extract($ʟ_args);
		unset($ʟ_args);

		$this->enterBlockLayer(2, get_defined_vars()) /* pos 4:5 */;
		try {
			$this->createTemplate('embed2.latte', [], "embed")->renderToContentType('html') /* pos 4:5 */;
		} finally {
			$this->leaveBlockLayer();
		}
	}


	/** {block a} on line 5 */
	public function blockA1(array $ʟ_args): void
	{
		echo 'nested embeds A';
	}
}
