<?php
%A%
		echo LR\HtmlHelpers::escapeText($var) /* pos %d%:%d% */;
		echo '


';
		$this->renderBlock('b', get_defined_vars()) /* pos %d%:%d% */;
		echo "\n";
		echo LR\HtmlHelpers::escapeText($var) /* pos %d%:%d% */;
		echo '


';
		ob_start(fn() => '') /* pos %d%:%d% */;
		try {
			(function () {
				extract(func_get_arg(0));
				echo '	';
				echo LR\HtmlHelpers::escapeText($var) /* pos %d%:%d% */;
				echo "\n";
				$var = 'blockmod' /* pos %d%:%d% */;
			})(get_defined_vars());
		} finally {
			$ʟ_fi = new LR\FilterInfo('html');
			echo LR\Helpers::convertTo($ʟ_fi, 'html', $this->filters->filterContent('trim', $ʟ_fi, ob_get_clean()));
		}
		echo "\n";
		echo LR\HtmlHelpers::escapeText($var) /* pos %d%:%d% */;
		echo '


';
		ob_start(fn() => '') /* pos %d%:%d% */;
		try {
			(function () {
				extract(func_get_arg(0));
				echo '	';
				echo LR\HtmlHelpers::escapeText($var) /* pos %d%:%d% */;
				echo "\n";
				$var = 'block' /* pos %d%:%d% */;
			})(get_defined_vars());
		} finally {
			$ʟ_fi = new LR\FilterInfo('html');
			echo ob_get_clean();
		}
		echo "\n";
		echo LR\HtmlHelpers::escapeText($var) /* pos %d%:%d% */;
	}


	public function prepare(): array
	{
		extract($this->params);

		$var = 'a' /* pos %d%:%d% */;
		return get_defined_vars();
	}


	/** {define a} on line %d% */
	public function blockA(array $ʟ_args): void
	{
		extract($this->params);
		extract($ʟ_args);
		unset($ʟ_args);

		echo '	';
		echo LR\HtmlHelpers::escapeText($var) /* pos %d%:%d% */;
		echo "\n";
		$var = 'define' /* pos %d%:%d% */;
	}


	/** {block b} on line %d% */
	public function blockB(array $ʟ_args): void
	{
		extract($this->params);
		extract($ʟ_args);
		unset($ʟ_args);

		echo '	';
		echo LR\HtmlHelpers::escapeText($var) /* pos %d%:%d% */;
		echo "\n";
		$var = 'blocknamed' /* pos %d%:%d% */;
	}
%A%
