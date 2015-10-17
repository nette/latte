<?php

/**
 * Test: Latte\Parser heredoc.
 */

use Tester\Assert;
use Latte\Parser;
use Latte\Token;


require __DIR__ . '/../bootstrap.php';

function test_parse_tokens($parser, $source, $expectedTokens) {
	$result = $parser->parse($source);
	$types = array_map(function (Token $token) {
		return $token->type;
	}, $result);
	Assert::same($expectedTokens, $types);
}

$parser = new \Latte\Parser();

test_parse_tokens(
	$parser,
	'{foo $x <<< EOF
lorem ispum
lorem ispum
EOF;
}',
	[ Token::MACRO_TAG ]
);

test_parse_tokens(
	$parser,
	'{foo $x <<< EOF
lorem ispum
lorem ispum
EOF;
}{bar $x <<< "EOF"
lorem ispum
lorem ispum
EOF;
}',
	[ Token::MACRO_TAG, Token::MACRO_TAG ]
);

test_parse_tokens(
	$parser,
	"{foo \$x <<< 'EOF'
{lorem ispum}
{lorem ispum}
EOF;
}",
	[ Token::MACRO_TAG ]
);

test_parse_tokens(
	$parser,
	"{foo \$x <<< 'EOF'
lorem ispum
lorem } ispum
EOF;
}{php function(){ \$x=<<<'EOL'
lorem ipsum;
EOL;
}}",
	[ Token::MACRO_TAG, Token::MACRO_TAG ]
);

test_parse_tokens(
	$parser,
	'<html title="{php<<<EOL
lorem ipsum
EOL
}">',
	[ Token::HTML_TAG_BEGIN, Token::HTML_ATTRIBUTE, Token::MACRO_TAG, Token::TEXT, Token::HTML_TAG_END ]
);
