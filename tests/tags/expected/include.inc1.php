<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{
	public const Source = '%a%.latte';


	public function main(array $ʟ_args): void
	{
%A%
		echo '<p>Included file #1</p>

';
		$this->createTemplate('include2.latte', ['localvar' => 20] + $this->params, 'include')->renderToContentType('html') /* line 3 */;
		echo "\n";
		$this->createTemplate('../include3.latte', $this->params, 'include')->renderToContentType('html') /* line 5 */;
		echo '
<textarea>
pre
</textarea>

Parent: ';
		echo LR\Filters::escapeHtmlText(basename($this->getReferringTemplate()->getName())) /* line 11 */;
		echo '/';
		echo LR\Filters::escapeHtmlText($this->getReferenceType()) /* line 11 */;
		echo "\n";
	}
}
