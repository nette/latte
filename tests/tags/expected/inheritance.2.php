<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{
	public const Blocks = [
		['content' => 'blockContent', 'title' => 'blockTitle', 'sidebar' => 'blockSidebar'],
	];


	public function main(array $ʟ_args): void
	{
%A%
		echo "\n";
		$this->renderBlock('content', get_defined_vars()) /* line %d% */;
		echo "\n";
		$this->renderBlock('sidebar', get_defined_vars()) /* line %d% */;
	}


	public function prepare(): array
	{
		extract($this->params);

		if (!$this->getReferringTemplate() || $this->getReferenceType() === 'extends') {
			foreach (array_intersect_key(['person' => '8'], $this->params) as $ʟ_v => $ʟ_l) {
				trigger_error("Variable \$$ʟ_v overwritten in foreach on line $ʟ_l");
			}
		}
		$this->parentName = 'parent';
		return get_defined_vars();
	}


	/** {block content} on line %d% */
	public function blockContent(array $ʟ_args): void
	{
		extract($this->params);
		extract($ʟ_args);
		unset($ʟ_args);

		echo '	<h1>';
		$this->renderBlock('title', get_defined_vars()) /* line %d% */;
		echo '</h1>

	<ul>
';
		foreach ($people as $person) /* line %d% */ {
			echo '		<li>';
			echo LR\Filters::escapeHtmlText($person) /* line %d% */;
			echo '</li>
';

		}

		echo '	</ul>
';
	}


	/** {block title} on line %d% */
	public function blockTitle(array $ʟ_args): void
	{
		echo 'Homepage ';
	}


	/** {block sidebar} on line %d% */
	public function blockSidebar(array $ʟ_args): void
	{
	}
}
