<?php

declare(strict_types=1);

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

	filter: {$hello |lower}
	{$hello |truncate:"10"|lower}
	{$hello |types , '' , ""	,	"$hello"  }
	EOD;

Assert::match(
	<<<'XX'
		%A%
				echo LR\Filters::escapeHtmlText($hello) /* pos 1:10 */;
				echo '
		Non-escaped: ';
				echo $hello /* pos 2:14 */;
				echo '
		Escaped expression: ';
				echo LR\Filters::escapeHtmlText('<' . 'b' . '>Putin is a war criminal' . '</b>') /* pos 3:21 */;
				echo '
		Non-escaped expression: ';
				echo '<' . 'b' . '>Zelensky is hero' . '</b>' /* pos 4:25 */;
				echo '
		Array access: ';
				echo LR\Filters::escapeHtmlText($people[1]) /* pos 5:15 */;
				echo '
		Condition: ';
				echo LR\Filters::escapeHtmlText($hello ? 'yes' : null) /* pos 6:12 */;
				echo ' ';
				echo LR\Filters::escapeHtmlText($hello ? 'yes' : null) /* pos 6:27 */;
				echo '
		Array: ';
				echo LR\Filters::escapeHtmlText(($this->filters->join)($hello ? ['a', 'b', 'c'] : null)) /* pos 7:8 */;
				echo ' ';
				echo LR\Filters::escapeHtmlText(($this->filters->join)(['a', 'b', $hello ? 'c' : null])) /* pos 7:36 */;
				echo '

		filter: ';
				echo LR\Filters::escapeHtmlText(($this->filters->lower)($hello)) /* pos 9:9 */;
				echo "\n";
				echo LR\Filters::escapeHtmlText(($this->filters->lower)(($this->filters->truncate)($hello, '10'))) /* pos 10:1 */;
				echo "\n";
				echo LR\Filters::escapeHtmlText(($this->filters->types)($hello, '', '', "{$hello}")) /* pos 11:1 */;
		%A%
		XX,
	$latte->compile($template),
);
