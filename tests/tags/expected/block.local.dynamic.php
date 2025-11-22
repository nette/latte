<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{
	public const Blocks = [
		'local' => ['static' => 'blockStatic'],
	];


	public function main(array $ʟ_args): void
	{
		extract($ʟ_args);
		unset($ʟ_args);

		echo "\n";
		$this->renderBlock('static', get_defined_vars()) /* pos %d%:%d% */;
		echo '

';
		foreach ($iterator = $ʟ_it = new Latte\Essential\CachingIterator(['dynamic', 'static'], $ʟ_it ?? null) as $name) /* pos %d%:%d% */ {
			$this->addBlock($ʟ_nm = (LR\Helpers::stringOrNull($ʟ_tmp = $name) ?? throw new InvalidArgumentException(sprintf('Block name must be a string, %s given.', get_debug_type($ʟ_tmp)))), 'html', [[$this, 'blockName']], 'local');
			$this->renderBlock($ʟ_nm, get_defined_vars());
		}
		$iterator = $ʟ_it = $ʟ_it->getParent();

		echo "\n";
		$this->renderBlock('dynamic', ['var' => 20] + [], 'html') /* pos %d%:%d% */;
		echo "\n";
		$this->renderBlock('static', ['var' => 30] + get_defined_vars(), 'html') /* pos %d%:%d% */;
		echo "\n";
		$this->renderBlock((LR\Helpers::stringOrNull($ʟ_tmp = $name . '') ?? throw new InvalidArgumentException(sprintf('Block name must be a string, %s given.', get_debug_type($ʟ_tmp)))), ['var' => 40] + [], 'html') /* pos %d%:%d% */;
		echo "\n";
		$this->addBlock($ʟ_nm = (LR\Helpers::stringOrNull($ʟ_tmp = "word{$name}") ?? throw new InvalidArgumentException(sprintf('Block name must be a string, %s given.', get_debug_type($ʟ_tmp)))), 'html', [[$this, 'blockWord_name']], 'local');
		$this->renderBlock($ʟ_nm, get_defined_vars());
		echo '

';
		$this->addBlock($ʟ_nm = (LR\Helpers::stringOrNull($ʟ_tmp = "strip{$name}") ?? throw new InvalidArgumentException(sprintf('Block name must be a string, %s given.', get_debug_type($ʟ_tmp)))), 'html', [[$this, 'blockStrip_name']], 'local');
		$this->renderBlock($ʟ_nm, get_defined_vars(), function ($s, $type) {
			$ʟ_fi = new LR\FilterInfo($type);
			return LR\Filters::convertTo($ʟ_fi, 'html', $this->filters->filterContent('striptags', $ʟ_fi, $s));
		});
		echo "\n";
	}


	public function prepare(): array
	{
		extract($this->params);

		if (!$this->getReferringTemplate() || $this->getReferenceType() === 'extends') {
			foreach (array_intersect_key(['name' => '8'], $this->params) as $ʟ_v => $ʟ_l) {
				trigger_error("Variable \$$ʟ_v overwritten in foreach on line $ʟ_l");
			}
		}
		$var = 10 /* pos %d%:%d% */;
		return get_defined_vars();
	}


	/** {block local static} on line %d% */
	public function blockStatic(array $ʟ_args): void
	{
		extract($this->params);
		extract($ʟ_args);
		unset($ʟ_args);

		echo '	Static block #';
		echo LR\Filters::escapeHtmlText($var) /* pos %d%:%d% */;
		echo "\n";
	}


	/** {block local $name} on line %d% */
	public function blockName(array $ʟ_args): void
	{
		extract($ʟ_args);
		unset($ʟ_args);

		echo '		Dynamic block #';
		echo LR\Filters::escapeHtmlText($var) /* pos %d%:%d% */;
		echo "\n";
	}


	/** {block local "word$name"} on line %d% */
	public function blockWord_name(array $ʟ_args): void
	{
		if (false) /* pos %d%:%d% */ {
			echo '<div></div>';
		}
	}


	/** {block local "strip$name"|striptags} on line %d% */
	public function blockStrip_name(array $ʟ_args): void
	{
		echo '<span>hello</span>';
	}
}
