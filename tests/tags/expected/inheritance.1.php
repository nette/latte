<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{
	public const Blocks = [
		['title' => 'blockTitle', 'content' => 'blockContent'],
	];


	public function main(array $ʟ_args): void
	{
%A%
		$this->createTemplate('inc', $this->params, 'includeblock')->renderToContentType('html') /* pos %d%:%d% */;
		echo "\n";
		$this->renderBlock('title', get_defined_vars()) /* pos %d%:%d% */;
		echo '

';
		$this->renderBlock('content', get_defined_vars()) /* pos %d%:%d% */;
	}


	public function prepare(): array
	{
		extract($this->params);

		if (!$this->getReferringTemplate() || $this->getReferenceType() === 'extends') {
			foreach (array_intersect_key(['person' => '11'], $this->params) as $ʟ_v => $ʟ_l) {
				trigger_error("Variable \$$ʟ_v overwritten in foreach on line $ʟ_l");
			}
		}
		$this->parentName = 'parent';
		$this->createTemplate('inc', $this->params, "import")->render() /* pos %d%:%d% */;
		return get_defined_vars();
	}


	/** {block title} on line %d% */
	public function blockTitle(array $ʟ_args): void
	{
		extract($this->params);
		extract($ʟ_args);
		unset($ʟ_args);

		echo 'Homepage | ';
		$this->renderParentBlock('title', get_defined_vars()) /* pos %d%:%d% */;
		$this->renderParentBlock('title', get_defined_vars()) /* pos %d%:%d% */;
	}


	/** {block content} on line %d% */
	public function blockContent(array $ʟ_args): void
	{
		extract($this->params);
		extract($ʟ_args);
		unset($ʟ_args);

		echo '	<ul>
';
		foreach ($people as $person) /* pos %d%:%d% */ {
			echo '		<li>';
			echo LR\Filters::escapeHtmlText($person) /* pos %d%:%d% */;
			echo '</li>
';

		}

		echo '	</ul>
	Parent: ';
		echo LR\Filters::escapeHtmlText(($this->global->fn->info2)($this, )) /* pos %d%:%d% */;
		echo "\n";
	}
}
