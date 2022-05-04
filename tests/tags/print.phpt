<?php

/**
 * n:class
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

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
				echo 'Escaped: ';
				echo LR\Filters::escapeHtmlText($hello) /* line 1 */;
				echo '
		Non-escaped: ';
				echo $hello /* line 2 */;
				echo '
		Escaped expression: ';
				echo LR\Filters::escapeHtmlText('<' . 'b' . '>Putin is a war criminal' . '</b>') /* line 3 */;
				echo '
		Non-escaped expression: ';
				echo '<' . 'b' . '>Zelensky is hero' . '</b>' /* line 4 */;
				echo '
		Array access: ';
				echo LR\Filters::escapeHtmlText($people[1]) /* line 5 */;
				echo '
		Condition: ';
				echo LR\Filters::escapeHtmlText($hello ? 'yes' : null) /* line 6 */;
				echo ' ';
				echo LR\Filters::escapeHtmlText($hello ? 'yes' : null) /* line 6 */;
				echo '
		Array: ';
				echo LR\Filters::escapeHtmlText(($this->filters->join)($hello ? (['a', 'b', 'c']) : null)) /* line 7 */;
				echo ' ';
				echo LR\Filters::escapeHtmlText(($this->filters->join)(['a', 'b', $hello ? 'c' : null])) /* line 7 */;
				echo '

		filter: ';
				echo LR\Filters::escapeHtmlText(($this->filters->lower)($hello)) /* line 9 */;
				echo "\n";
				echo LR\Filters::escapeHtmlText(($this->filters->lower)(($this->filters->truncate)($hello, "10"))) /* line 10 */;
				echo "\n";
				echo LR\Filters::escapeHtmlText(($this->filters->types)($hello , '' , "" , "$hello" )) /* line 11 */;
		%A%
		XX
,
	$latte->compile($template),
);
