<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{
	public const Source = 'parent';

	public const Blocks = [
		['title' => 'blockTitle', 'sidebar' => 'blockSidebar'],
	];


	public function main(array $ʟ_args): void
	{
		extract($ʟ_args);
		unset($ʟ_args);

		echo '<!DOCTYPE html>
<head>
	<title>';
		$this->renderBlock('title', get_defined_vars(), function ($s, $type) {
			$ʟ_fi = new LR\FilterInfo($type);
			return LR\Filters::convertTo($ʟ_fi, 'html', $this->filters->filterContent('upper', $ʟ_fi, $this->filters->filterContent('stripHtml', $ʟ_fi, $s)));
		}) /* line %d%:%d% */;
		echo '</title>
</head>

<body>
	<div id="sidebar">
';
		$this->renderBlock('sidebar', get_defined_vars()) /* line %d%:%d% */;
		echo '	</div>

	<div id="content">
';
		$this->renderBlock('content', [], 'html') /* line %d%:%d% */;
		echo "\n";
		$this->renderBlock('content', [], function ($s, $type) {
			$ʟ_fi = new LR\FilterInfo($type);
			return LR\Filters::convertTo($ʟ_fi, 'html', $this->filters->filterContent('upper', $ʟ_fi, $this->filters->filterContent('stripHtml', $ʟ_fi, $s)));
		}) /* line %d%:%d% */;
		echo '	</div>
</body>
</html>
Parent: ';
		echo LR\Filters::escapeHtmlText(basename($this->getReferringTemplate()->getName())) /* line %d%:%d% */;
		echo '/';
		echo LR\Filters::escapeHtmlText($this->getReferenceType()) /* line %d%:%d% */;
		echo "\n";
	}


	public function prepare(): array
	{
		extract($this->params);

		$class ??= array_key_exists('class', get_defined_vars()) ? null : null;
		$namespace ??= array_key_exists('namespace', get_defined_vars()) ? null : null;
		$top ??= array_key_exists('top', get_defined_vars()) ? null : true /* line %d%:%d% */;
		return get_defined_vars();
	}


	/** {block title|stripHtml|upper} on line %d% */
	public function blockTitle(array $ʟ_args): void
	{
		echo 'My website';
	}


	/** {block sidebar} on line %d% */
	public function blockSidebar(array $ʟ_args): void
	{
		echo '		<ul>
			<li><a href="/">Homepage</a></li>
		</ul>
';
	}
}
