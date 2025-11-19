<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{

	public function main(array $ʟ_args): void
	{
%A%
		$this->createTemplate((LR\Helpers::stringOrNull($ʟ_tmp = 'subdir/include1.latte' . '') ?? throw new InvalidArgumentException(sprintf('Template name must be a string, %s given.', get_debug_type($ʟ_tmp)))), ['localvar' => 10] + $this->params, 'include')->renderToContentType(function ($s, $type) {
			$ʟ_fi = new LR\FilterInfo($type);
			return LR\Helpers::convertTo($ʟ_fi, 'html', $this->filters->filterContent('indent', $ʟ_fi, $s));
		}) /* pos %d%:%d% */;
	}
}
