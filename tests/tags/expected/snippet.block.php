<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{
	protected const BLOCKS = [
		0 => ['block1' => 'blockBlock1', 'block2' => 'blockBlock2'],
		'snippet' => ['snippet' => 'blockSnippet', 'outer' => 'blockOuter'],
	];


	public function main(): array
	{
%A%
		$this->renderBlock('block1', get_defined_vars()) /* line %d% */;
		echo '

<div id="';
		echo htmlspecialchars($this->global->snippetDriver->getHtmlId('outer'));
		echo '">';
		$this->renderBlock('outer', [], null, 'snippet') /* line %d% */;
		echo '</div>';
%A%
	}


	/** {block block1} on line %d% */
	public function blockBlock1(array $ʟ_args): void
	{
		extract($this->params);
		extract($ʟ_args);
		unset($ʟ_args);
		echo '<div';
		echo ' id="' . htmlspecialchars($this->global->snippetDriver->getHtmlId('snippet')) . '"';
		echo '>
';
		$this->renderBlock('snippet', [], null, 'snippet');
		echo '</div>
';

	}


	/** {block block2} on line %d% */
	public function blockBlock2(array $ʟ_args): void
	{
		extract($this->params);
		extract($ʟ_args);
		unset($ʟ_args);
		echo '<div';
		echo ' id="' . htmlspecialchars($this->global->snippetDriver->getHtmlId($ʟ_nm = "inner-{$id}")) . '"';
		echo '>
';
		$this->global->snippetDriver->enter($ʟ_nm, 'dynamic') /* line %d% */;
		try {
			echo '		dynamic
';
		} finally {
			$this->global->snippetDriver->leave();
		}
		echo '</div>
';

	}


	/** {snippet snippet} on line %d% */
	public function blockSnippet(array $ʟ_args): void
	{
		extract($this->params);
		extract($ʟ_args);
		unset($ʟ_args);
		$this->global->snippetDriver->enter("snippet", 'static');
		try {
			echo '		static
';
		} finally {
			$this->global->snippetDriver->leave();
		}

	}


	/** {snippet outer} on line %d% */
	public function blockOuter(array $ʟ_args): void
	{
		extract($this->params);
		extract($ʟ_args);
		unset($ʟ_args);
		$this->global->snippetDriver->enter("outer", 'static');
		try {
			echo 'begin
';
			$this->renderBlock('block2', get_defined_vars()) /* line %d% */;
			echo 'end
';
		} finally {
			$this->global->snippetDriver->leave();
		}

	}

}
