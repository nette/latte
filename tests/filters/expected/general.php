<?php declare(strict_types=1);
%A%
final class Template%a% extends Latte\Runtime\Template
{

	public function main(array $ʟ_args): void
	{
%A%
		echo '<ul>
	<li>';
		echo ((is_object($ʟ_val = ($this->filters->h2)(($this->filters->h1)($hello))) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos %d%:%d% */;
		echo '</li>
	<li>';
		echo ($this->filters->h2)(($this->filters->h1)($hello)) /* pos %d%:%d% */;
		echo '</li>
	<li>';
		echo ((is_object($ʟ_val = ($this->filters->h1)(($this->filters->h2)($hello))) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos %d%:%d% */;
		echo '</li>
</ul>

<ul>
	<li>';
		echo ((is_object($ʟ_val = ($this->filters->types)((int) $hello * 0, 0, 0.0, '0')) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos %d%:%d% */;
		echo '</li>
	<li>';
		echo ((is_object($ʟ_val = ($this->filters->types)((int) $hello * 1, 1, '1')) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos %d%:%d% */;
		echo '</li>
	<li>';
		echo ((is_object($ʟ_val = ($this->filters->types)($hello, true, null, false)) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos %d%:%d% */;
		echo '</li>
	<li>';
		echo ((is_object($ʟ_val = ($this->filters->types)($hello, true, null, false)) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos %d%:%d% */;
		echo '</li>
	<li>';
		echo ((is_object($ʟ_val = ($this->filters->types)($hello, '', '', "{$hello}")) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos %d%:%d% */;
		echo '</li>
</ul>



';
		ob_start(fn() => '') /* pos %d%:%d% */;
		try {
			(function () {
				extract(func_get_arg(0));
				echo '  <a   href="#"
> test</a>
A   A

<script>
// comment
alert();
</script>
';

			})(get_defined_vars());
		} finally {
			$ʟ_fi = new LR\FilterInfo('html');
			echo LR\Helpers::convertTo($ʟ_fi, 'html', $this->filters->filterContent('strip', $ʟ_fi, ob_get_clean()));
		}
		echo '


<p>
Nested blocks: ';
		ob_start(fn() => '') /* pos %d%:%d% */;
		try {
			(function () {
				extract(func_get_arg(0));
				echo ' Outer   ';
				ob_start(fn() => '') /* pos %d%:%d% */;
				try {
					(function () {
						extract(func_get_arg(0));
						echo ' Inner Block ';

					})(get_defined_vars());
				} finally {
					$ʟ_fi = new LR\FilterInfo('html');
					echo LR\Helpers::convertTo($ʟ_fi, 'html', $this->filters->filterContent('upper', $ʟ_fi, $this->filters->filterContent('striphtml', $ʟ_fi, ob_get_clean())));
				}
				echo '  Block ';

			})(get_defined_vars());
		} finally {
			$ʟ_fi = new LR\FilterInfo('html');
			echo LR\Helpers::convertTo($ʟ_fi, 'html', $this->filters->filterContent('truncate', $ʟ_fi, $this->filters->filterContent('striphtml', $ʟ_fi, ob_get_clean()), 20));
		}
		echo '
</p>

Breaklines: ';
		echo ((is_object($ʟ_val = ($this->filters->breakLines)('hello
bar')) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos %d%:%d% */;
		echo "\n";
	}
}
