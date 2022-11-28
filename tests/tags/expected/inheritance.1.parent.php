<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{
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
		}) /* line %d% */;
		echo '</title>
</head>

<body>
	<div id="sidebar">
';
		$this->renderBlock('sidebar', get_defined_vars()) /* line %d% */;
		echo '	</div>

	<div id="content">
';
		$this->renderBlock('content', [], 'html') /* line %d% */;
		echo "\n";
		$this->renderBlock('content', [], function ($s, $type) {
			$ʟ_fi = new LR\FilterInfo($type);
			return LR\Filters::convertTo($ʟ_fi, 'html', $this->filters->filterContent('upper', $ʟ_fi, $this->filters->filterContent('stripHtml', $ʟ_fi, $s)));
		}) /* line %d% */;
		echo '	</div>
</body>
</html>
Parent: ';
		echo LR\Filters::escapeHtmlText(basename($this->getReferringTemplate()->getName())) /* line %d% */;
		echo '/';
		echo LR\Filters::escapeHtmlText($this->getReferenceType()) /* line %d% */;
		echo "\n";
	}


	public function prepare(): array
	{
		extract($this->params);

		extract(['class' => null, 'namespace' => null, 'top' => true], EXTR_SKIP) /* line %d% */;
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
