<?php

// DOC strings

declare(strict_types=1);

use Latte\Compiler\TagLexer;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	<<<END
	END;


	<<<END
	  END;


	<<<'END'
	END;


	<<<'END'
	  END;


	<<<END
	a
	END;


	<<<END
	ab
	END;

	<<<END
	  ab
	  END;

	<<<END
	abc
	   END;

	<<<END

	   END;

	<<<'END'
	ab
	END;

	<<<'END'
	  ab
	  END;

	<<<'END'
	abc
	   END;

	<<<'END'

	   END;
	XX;

$tokens = (new TagLexer)->tokenize($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportTokens($tokens),
);

__halt_compiler();
#1:1   Php_StartHeredoc '<<<END\n'
#2:1   Php_EndHeredoc  'END'
#2:4   ';'
#2:5   Php_Whitespace  '\n\n\n'
#5:1   Php_StartHeredoc '<<<END\n'
#6:1   Php_EndHeredoc  '  END'
#6:6   ';'
#6:7   Php_Whitespace  '\n\n\n'
#9:1   Php_StartHeredoc '<<<'END'\n'
#10:1  Php_EndHeredoc  'END'
#10:4  ';'
#10:5  Php_Whitespace  '\n\n\n'
#13:1  Php_StartHeredoc '<<<'END'\n'
#14:1  Php_EndHeredoc  '  END'
#14:6  ';'
#14:7  Php_Whitespace  '\n\n\n'
#17:1  Php_StartHeredoc '<<<END\n'
#18:1  Php_EncapsedAndWhitespace 'a\n'
#19:1  Php_EndHeredoc  'END'
#19:4  ';'
#19:5  Php_Whitespace  '\n\n\n'
#22:1  Php_StartHeredoc '<<<END\n'
#23:1  Php_EncapsedAndWhitespace 'ab\n'
#24:1  Php_EndHeredoc  'END'
#24:4  ';'
#24:5  Php_Whitespace  '\n\n'
#26:1  Php_StartHeredoc '<<<END\n'
#27:1  Php_EncapsedAndWhitespace '  ab\n'
#28:1  Php_EndHeredoc  '  END'
#28:6  ';'
#28:7  Php_Whitespace  '\n\n'
#30:1  Php_StartHeredoc '<<<END\n'
#31:1  Php_EncapsedAndWhitespace 'abc\n'
#32:1  Php_EndHeredoc  '   END'
#32:7  ';'
#32:8  Php_Whitespace  '\n\n'
#34:1  Php_StartHeredoc '<<<END\n'
#35:1  Php_EncapsedAndWhitespace '\n'
#36:1  Php_EndHeredoc  '   END'
#36:7  ';'
#36:8  Php_Whitespace  '\n\n'
#38:1  Php_StartHeredoc '<<<'END'\n'
#39:1  Php_EncapsedAndWhitespace 'ab\n'
#40:1  Php_EndHeredoc  'END'
#40:4  ';'
#40:5  Php_Whitespace  '\n\n'
#42:1  Php_StartHeredoc '<<<'END'\n'
#43:1  Php_EncapsedAndWhitespace '  ab\n'
#44:1  Php_EndHeredoc  '  END'
#44:6  ';'
#44:7  Php_Whitespace  '\n\n'
#46:1  Php_StartHeredoc '<<<'END'\n'
#47:1  Php_EncapsedAndWhitespace 'abc\n'
#48:1  Php_EndHeredoc  '   END'
#48:7  ';'
#48:8  Php_Whitespace  '\n\n'
#50:1  Php_StartHeredoc '<<<'END'\n'
#51:1  Php_EncapsedAndWhitespace '\n'
#52:1  Php_EndHeredoc  '   END'
#52:7  ';'
#52:8  End             ''
