<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{

	public function main(array $ʟ_args): void
	{
%A%
		echo '<p>Included file #1</p>

';
		$this->createTemplate('include2.latte', ['localvar' => 20] + $this->params, 'include')->renderToContentType('html') /* line 3:1 */;
		echo "\n";
		$this->createTemplate('../include3.latte', $this->params, 'include')->renderToContentType('html') /* line 5:1 */;
		echo '
<textarea>
pre
</textarea>

Parent: ';
		echo LR\HtmlHelpers::escapeText(($this->global->fn->info)($this, )) /* line 11:9 */;
		echo "\n";
	}
}
