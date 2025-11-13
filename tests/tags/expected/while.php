<?php
%A%
		while ($i++ < 10) /* line 2:1 */ {
			echo '	';
			echo LR\HtmlHelpers::escapeText($i) /* line 3:2 */;
			echo "\n";

		}
		echo '

';
		do /* line 7:1 */ {
			echo '	';
			echo LR\HtmlHelpers::escapeText($i) /* line 8:2 */;
			echo "\n";

		}
		while ($i++ < 10);
		echo '

';
		while ($i++ < 10) /* line 12:1 */ {
			if (true) /* line 13:2 */ break;
			if (true) /* line 14:2 */ continue;
			echo '	';
			echo LR\HtmlHelpers::escapeText($i) /* line 15:2 */;
			echo "\n";

		}
%A%
