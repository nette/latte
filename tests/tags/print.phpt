<?php declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = createLatte();

$template = <<<'EOD'
	Escaped: {$hello}
	Non-escaped: {$hello|noescape}
	Escaped expression: {='<' . 'b' . '>Putin is a war criminal' . '</b>'}
	Non-escaped expression: {='<' . 'b' . '>Zelensky is hero' . '</b>'|noescape}
	Array access: {$people[1]}
	Condition: {$hello ? yes} {=$hello ? yes}
	Array: {$hello ? ([a, b, c])|join} {=[a, b, $hello ? c]|join}

	filter: {$hello ?|lower}
	{$hello |truncate:"10"|lower}
	{$hello |types , '' , ""	,	"$hello"  }
	EOD;

Assert::match(
	<<<'XX'
		%A%
				echo 'Escaped: ';
				echo ((is_object($ʟ_val = $hello) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos 1:10 */;
				echo '
		Non-escaped: ';
				echo $hello /* pos 2:14 */;
				echo '
		Escaped expression: ';
				echo ((is_object($ʟ_val = '<' . 'b' . '>Putin is a war criminal' . '</b>') ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos 3:21 */;
				echo '
		Non-escaped expression: ';
				echo '<' . 'b' . '>Zelensky is hero' . '</b>' /* pos 4:25 */;
				echo '
		Array access: ';
				echo ((is_object($ʟ_val = $people[1]) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos 5:15 */;
				echo '
		Condition: ';
				echo ((is_object($ʟ_val = $hello ? 'yes' : null) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos 6:12 */;
				echo ' ';
				echo ((is_object($ʟ_val = $hello ? 'yes' : null) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos 6:27 */;
				echo '
		Array: ';
				echo ((is_object($ʟ_val = ($this->filters->join)($hello ? ['a', 'b', 'c'] : null)) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos 7:8 */;
				echo ' ';
				echo ((is_object($ʟ_val = ($this->filters->join)(['a', 'b', $hello ? 'c' : null])) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos 7:36 */;
				echo '

		filter: ';
				echo ((is_object($ʟ_val = (($ʟ_tmp = $hello) === null ? null : ($this->filters->lower)($ʟ_tmp))) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos 9:9 */;
				echo "\n";
				echo ((is_object($ʟ_val = ($this->filters->lower)(($this->filters->truncate)($hello, '10'))) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos 10:1 */;
				echo "\n";
				echo ((is_object($ʟ_val = ($this->filters->types)($hello, '', '', "{$hello}")) ? LR\Helpers::convertTrusted($ʟ_val, 'html') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos 11:1 */;
		%A%
		XX,
	$latte->compile($template),
);
