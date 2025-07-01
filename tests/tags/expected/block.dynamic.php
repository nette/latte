<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{
	public const Blocks = [
		['static' => 'blockStatic'],
	];


	public function main(array $ʟ_args): void
	{
		extract($ʟ_args);
		unset($ʟ_args);

		echo "\n";
		$this->renderBlock('static', get_defined_vars()) /* line %d%:%d% */;
		echo '

';
		foreach ($iterator = $ʟ_it = new Latte\Essential\CachingIterator(['dynamic', 'static'], $ʟ_it ?? null) as $name) /* line %d%:%d% */ {
			$this->addBlock($ʟ_nm = (is_string($ʟ_tmp = $name) ? $ʟ_tmp : throw new InvalidArgumentException(sprintf('Block name must be a string, %s given.', get_debug_type($ʟ_tmp)))), 'html', [[$this, 'blockName']], 0);
			$this->renderBlock($ʟ_nm, get_defined_vars());
		}
		$iterator = $ʟ_it = $ʟ_it->getParent();

		echo "\n";
		$this->renderBlock('dynamic', ['var' => 20] + [], 'html') /* line %d%:%d% */;
		echo "\n";
		$this->renderBlock('static', ['var' => 30] + get_defined_vars(), 'html') /* line %d%:%d% */;
		echo "\n";
		$this->renderBlock((is_string($ʟ_tmp = $name . '') ? $ʟ_tmp : throw new InvalidArgumentException(sprintf('Block name must be a string, %s given.', get_debug_type($ʟ_tmp)))), ['var' => 40] + [], 'html') /* line %d%:%d% */;
		echo "\n";
		$this->addBlock($ʟ_nm = (is_string($ʟ_tmp = "word{$name}") ? $ʟ_tmp : throw new InvalidArgumentException(sprintf('Block name must be a string, %s given.', get_debug_type($ʟ_tmp)))), 'html', [[$this, 'blockWord_name']], 0);
		$this->renderBlock($ʟ_nm, get_defined_vars());
		echo '

';
		$this->addBlock($ʟ_nm = (is_string($ʟ_tmp = "strip{$name}") ? $ʟ_tmp : throw new InvalidArgumentException(sprintf('Block name must be a string, %s given.', get_debug_type($ʟ_tmp)))), 'html', [[$this, 'blockStrip_name']], 0);
		$this->renderBlock($ʟ_nm, get_defined_vars(), function ($s, $type) {
			$ʟ_fi = new LR\FilterInfo($type);
			return LR\Filters::convertTo($ʟ_fi, 'html', $this->filters->filterContent('striptags', $ʟ_fi, $s));
		});
		echo '

';
		$this->addBlock($ʟ_nm = (is_string($ʟ_tmp = rand() < 5 ? 'a' : 'b') ? $ʟ_tmp : throw new InvalidArgumentException(sprintf('Block name must be a string, %s given.', get_debug_type($ʟ_tmp)))), 'html', [[$this, 'blockRand_5_a_b']], 0);
		$this->renderBlock($ʟ_nm, get_defined_vars());
	}


	public function prepare(): array
	{
		extract($this->params);

		if (!$this->getReferringTemplate() || $this->getReferenceType() === 'extends') {
			foreach (array_intersect_key(['name' => '8'], $this->params) as $ʟ_v => $ʟ_l) {
				trigger_error("Variable \$$ʟ_v overwritten in foreach on line $ʟ_l");
			}
		}
		$var = 10 /* line %d%:%d% */;
		return get_defined_vars();
	}


	/** {block static} on line %d% */
	public function blockStatic(array $ʟ_args): void
	{
		extract($this->params);
		extract($ʟ_args);
		unset($ʟ_args);

		echo '	Static block #';
		echo LR\Filters::escapeHtmlText($var) /* line %d%:%d% */;
		echo "\n";
	}


	/** {block $name} on line %d% */
	public function blockName(array $ʟ_args): void
	{
		extract($ʟ_args);
		unset($ʟ_args);

		echo '		Dynamic block #';
		echo LR\Filters::escapeHtmlText($var) /* line %d%:%d% */;
		echo "\n";
	}


	/** {block "word$name"} on line %d% */
	public function blockWord_name(array $ʟ_args): void
	{
		if (false) /* line %d%:%d% */ {
			echo '<div></div>';
		}
	}


	/** {block "strip$name"|striptags} on line %d% */
	public function blockStrip_name(array $ʟ_args): void
	{
		echo '<span>hello</span>';
	}


	/** {block rand() < 5 ? a : b} on line %d% */
	public function blockRand_5_a_b(array $ʟ_args): void
	{
		echo ' expression ';
	}
}
