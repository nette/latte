<?php declare(strict_types=1);
%A%
final class Template%a% extends Latte\Runtime\Template
{

	public function main(array $ʟ_args): void
	{
%A%
		echo '<p>Included file #1</p>

';
		$this->createTemplate('include2.latte', ['localvar' => 20] + $this->params, 'include')->renderToContentType('html') /* pos 3:1 */;
		echo "\n";
		$this->createTemplate('../include3.latte', $this->params, 'include')->renderToContentType('html') /* pos 5:1 */;
		echo '
<textarea>
pre
</textarea>

Parent: ';
		echo ((is_object($ʟ_val = ($this->global->fn->info)($this, )) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos 11:9 */;
		echo "\n";
	}
}
