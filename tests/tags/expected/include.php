<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{

	public function main(array $ʟ_args): void
	{
%A%
		$this->createTemplate('subdir/include1.latte' . '', ['localvar' => 10] + $this->params, 'include')->renderToContentType(function ($s, $type) {
			$ʟ_fi = new LR\FilterInfo($type);
			return LR\Filters::convertTo($ʟ_fi, 'html', $this->filters->filterContent('indent', $ʟ_fi, $s));
		}) /* line %d% */;
	}
}
